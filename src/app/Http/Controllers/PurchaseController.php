<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order; //追加：注文を保存するためにモデルをインポート
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest; //追加：住所変更のバリデーション用リクエスト
use Stripe\Stripe;// 追加：Stripeクラスを使うためにインポート
use Stripe\Checkout\Session;// 追加：Stripeのセッションを作成するためにインポート
use Illuminate\Support\Facades\DB; //追加：安全なデータ更新（トランザクション）のため

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        // 1. URLの ID から購入しようとしている商品の情報を取得
        $item = Item::findOrFail($item_id);

        // 2. 現在ログインしているユーザーの情報を取得（住所や郵便番号を表示するため）
        $user = Auth::user();

        // 💡 支払い方法の選択肢（今回はセレクトボックスなので、配列で用意してビューに渡すとスマートです）
        $payment_methods = [
            'konbini' => 'コンビニ払い',
            'card'        => 'カード支払い',
        ];

        // 3. 準備したデータをビューに渡して画面を表示
        // ※ views/purchases/index.blade.php を作成する場合
        return view('items.purchase', compact('item', 'user', 'payment_methods'));
    }

    /**
     * 配送先住所 変更画面を表示する
     */
    public function editAddress($item_id)
    {
        // 1. 現在ログインしているユーザーの情報を取得（現在の住所をフォームの初期値にするため）
        $user = auth()->user();

        // 2. 見本画像（住所の変更）画面を表示。item_idを一緒に渡します
         return view('users.address_edit', compact('user', 'item_id'));
    }

    /**
     * 配送先住所を一時的、またはDBに更新する
     */
    public function updateAddress(AddressRequest $request, $item_id)
    {
        //ここでバリデーション（入力必須など）を行う
        $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
        ]);

        //ユーザー情報を更新して保存
        $user = auth()->user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        //更新が完了したら、元の商品の購入手続き画面へ戻す
        return redirect()->route('items.purchase', ['item_id' => $item_id]);
    }

     /**
     * 【修正】Stripeでの決済成功後に呼び出される購入確定処理
     */
    public function success(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 購入時のユーザー住所を「送付先住所」として結合して保持
        $shippingAddress = "〒{$user->postal_code} {$user->address} {$user->building}";

        try {
            DB::transaction(function () use ($item, $user, $shippingAddress) {
                // 1. 同時購入を防ぎつつ、商品のステータスを売り切れに更新
                $item->update(['is_sold' => true]);

                // 2. orders テーブルに購入履歴を保存
                Order::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'stripe_payment_intent_id' => 'paid_via_stripe_' . uniqid(), // 必要に応じてStripeのセッションID等を入れる
                    'status' => 'paid',
                    'shipping_address' => $shippingAddress,
                ]);
            });
            
            // 【要件4】商品は購入した後の遷移先は商品一覧画面
            return redirect()->route('items.index')->with('success', '購入が完了しました！');

        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', '購入確定処理に失敗しました。');
        }
    }

    /**
     * 決済キャンセル時に呼び出される処理（ルートの cancel に対応）
     */
    public function cancel($item_id)
    {
        // 商品詳細画面に戻す（要件やルート名に合わせて調整してください）
        return redirect()->route('items.show', ['item_id' => $item_id])->with('error', '決済がキャンセルされました。');
    }

   /**
     * Stripe決済画面へリダイレクトする
     */
    public function checkout(PurchaseRequest $request, $item_id)
    {
        
        // 1. 商品の存在チェック
        $item = Item::findOrFail($item_id);

        // 2. 画面から送られてきた支払い方法（'card' または 'konbini'）をそのまま取得
        $paymentMethod = $request->input('payment_method');

        // 3. Stripeのシークレットキーを設定（.envから自動読み込み）
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 4. Stripeの決済セッションを作成
            $session = Session::create([
                'success_url' => route('purchase.success', ['item_id' => $item->id]). '?method=' . $paymentMethod, // 成功時の戻り先
                'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]),      // キャンセル時の戻り先
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name, // データベースの商品名
                        ],
                        'unit_amount' => $item->price, // データベースの金額
                    ],
                    'quantity' => 1,
                ]],
                'payment_method_types' => [
                    $paymentMethod // 画面から届いた文字列（card / konbini）がそのまま入ります
                ],
            ]);

            // 5. Stripeが発行した決済画面URLへリダイレクト
            return redirect()->away($session->url);

        } catch (\Exception $e) {
             return redirect()->back()->with('error', '決済画面の起動に失敗しました：' . $e->getMessage());
        }
    }
}
