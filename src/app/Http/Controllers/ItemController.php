<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Itemモデルをインポート
use App\Models\Comment; // Commentモデルをインポート
use App\Models\Category;   // カテゴリモデル
use App\Models\Condition;  // 商品の状態モデル
use Illuminate\Support\Facades\Auth; // Authクラスを使うために追加
use App\Http\Requests\ExhibitionRequest; // 出品バリデーション用

class ItemController extends Controller
{
   public function index(Request $request)
   {
     //ログイン中かつメール認証がまだの場合は、トップを見せずに認証画面へ戻す
    if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    
    // 1. 画面（リクエスト）から「検索キーワード」と「タブ状態」を取得
    $search = $request->input('search');
    $page = $request->input('page', 'recommend'); // デフォルトは 'recommend'

    // 2. クエリの初期化
    $query = Item::query();

    // 3. 【条件2】モデルのローカルスコープを呼び出し（部分一致検索）
    $query->keyword($search);

    // 4. タブ状態（おすすめ / マイリスト）によって条件を分岐
    if ($page === 'mylist') {
        // 要件1 & 4: マイリストタブの挙動
        if (Auth::check()) {
            // ログイン中：自分がいいね（お気に入り）した商品のみに絞り込む
            $current_user_id = Auth::id();
            $query->whereHas('favorites', function ($q) use ($current_user_id) {
                $q->where('user_id', $current_user_id);
            });
        } else {
            // 未認証（未ログイン）：要件に合わせ、何も表示しないようにする
            $query->whereNull('id');
        }
    } else {
        // おすすめタブ：自分が出品した商品は表示されないように除外（元の仕様を維持）
        $current_user_id = Auth::id() ?? 999;
        $query->where('user_id', '!=', $current_user_id);
    }

    // 5. 最終的なデータをデータベースから取得
    // ※購入済み商品（Sold）も画面で表示するため、ここでは除外せずすべて取得します
    $items = $query->get();

    // 6. ビューに現在の「商品データ」「検索文字」「タブ」を一緒に渡す
    return view('items.index', compact('items', 'search', 'page'));
   }

    /**
     * 商品出品画面を表示する(FN028・FN029用画面)
     */
    public function create()
    {
        //DBから全件取得してビューに渡す
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.listing', compact('categories', 'conditions'));
    }


    public function store(ExhibitionRequest $request)
    {
        // 画像保存(FN029)
        $path = $request->file('image')->store('products','public');

        // 商品情報保存(FN028)
        $item = new Item();
        $item->name = $request->name;
        $item->brand_name = $request->brand_name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->img_url = $path; // 画像のパスを保存
        $item->user_id = auth()->id(); // 出品者のユーザーIDを保存

        //【要件3対応】商品の状態（プルダウン選択されたID）を保存
        $item->condition_id = $request->condition_id; 

        $item->save();
        
        // 3. 【要件2対応】カテゴリは複数選択が可能であること（中間テーブルへの保存）
        // ※HTML側の各カテゴリ選択（チェックボックス等）の name 属性を「categories[]」にしてください
        if ($request->has('categories')) {
            // Itemモデルに定義されている categories() リレーションを使って中間テーブルに一括保存
            $item->categories()->attach($request->categories);
        }

        return redirect()->route('items.index');
    }

    /**
     * 商品詳細情報取得(FN017)
     */
    public function show($item_id)
    {
        // 未承認（ログインなし）ユーザーでも閲覧可能
        $item = Item::with(['categories', 'favorites', 'comments.user', 'condition'])->findOrFail($item_id);

        // ログインユーザーがこの商品を「いいね」済みかどうかを判定（FN018用フラグ）
        $is_favorited = false;
        if (Auth::check()) {
            $is_favorited = $item->favorites->contains(Auth::id());
        }
        return view('items.show', compact('item', 'is_favorited'));
    }

}
