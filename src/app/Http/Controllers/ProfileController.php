<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //画像削除・保存に必要
use App\Models\Item;
use App\Models\Order; // 注文保存のためにインポート
use App\Http\Requests\ProfileRequest; //プロフィールバリデーション用

class ProfileController extends Controller
{
    /**
     * マイページ（プロフィール画面）を表示する
     */
    public function show(Request $request) //メソッド名を show に指定
    {
        // 1. 現在ログインしているユーザーの情報を取得
        $user = Auth::user();

        // 2. 選択されているタブの状態を取得（デフォルトは 'sell'：出品した商品）
        $page = $request->input('page', 'sell');

        // 3. タブの状態に応じて取得するデータを切り替える
        if ($page === 'buy') {
            // 🛒 購入した商品（ご自身のDB設計に合わせて buyer_id などを調整してください）
            $purchasedItemIds = Order::where('user_id', $user->id)->pluck('item_id');
            $items = Item::whereIn('id', $purchasedItemIds)->get();
        } else {
            // 📦 出品した商品
            $items = Item::where('user_id', $user->id)->get();
        }

        // 4. ビューにデータを渡す
        return view('users.profile', compact('user', 'page', 'items'));
    }
    
    /**
     * プロフィール編集画面を表示する (GET)
     * 
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('users.edit_profile', compact('user'));
    }

    
    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        
        // 🛠️ パターンA仕様：users テーブルのカラムへ直接、入力データを代入します
        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        // 画像がアップロードされた場合の処理
        if ($request->hasFile('img_url')) {
            // 古い画像があればストレージから削除
            if ($user->img_url) {
                Storage::disk('public')->delete($user->img_url);
            }

            // 新しい画像を「profiles」フォルダに保存してパスを代入
            $path = $request->file('img_url')->store('profiles', 'public');
            $user->img_url = $path;
        }
        
       
        // usersテーブルに一括保存
        $user->save();

        // マイページへリダイレクト
        return redirect('/');
    }
}
