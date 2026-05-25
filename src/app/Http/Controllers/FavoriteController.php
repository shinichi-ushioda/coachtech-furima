<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
 

    /**
     * いいねを追加する(5月20日)
     */
    public function store($id)
    {
        $item = Item::findOrFail($id);

        // 自分が出品した商品にはいいねできないようにする
        if ($item->user_id === Auth::id()) {
            return back()->with('error', '自分が出品した商品にはいいねできません。');
        }

        // 二重登録を防ぐため、まだいいねしていない場合のみ登録
        if (!$item->favorites()->where('user_id', Auth::id())->exists()) {
            $item->favorites()->attach(Auth::id());
        }

        return back();
    }

    /**
     * いいねを解除する
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // リレーションを解除
        $item->favorites()->detach(Auth::id());

        return back();
    }
}


