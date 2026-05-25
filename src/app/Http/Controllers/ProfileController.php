<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ProfileController extends Controller
{
    /**
     * マイページ（プロフィール画面）を表示する
     */
    public function profile(Request $request) //メソッド名を profile に指定
    {
        // 1. 現在ログインしているユーザーの情報を取得
        $user = Auth::user();

        // 2. 選択されているタブの状態を取得（デフォルトは 'sell'：出品した商品）
        $tab = $request->input('tab', 'sell');

        // 3. タブの状態に応じて取得するデータを切り替える
        if ($tab === 'buy') {
            // 🛒 購入した商品（ご自身のDB設計に合わせて buyer_id などを調整してください）
            $items = Item::where('buyer_id', $user->id)->get();
        } else {
            // 📦 出品した商品
            $items = Item::where('user_id', $user->id)->get();
        }

        // 4. ビューにデータを渡す
        return view('users.profile', compact('user', 'tab', 'items'));
    }

    public function update(Request $request)
    {
    $user = Auth::user();
    $user->update([
        'name' => $request->name,
        'postal_code' => $request->postal_code,
        'address' => $request->address,
        'building' => $request->building,
    ]);

    // 更新後はトップページ（商品一覧など）へ移動させる
    return redirect('/');
    }
}
