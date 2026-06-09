<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->user_id === Auth::id()) {
            return back()->with('error', '自分が出品した商品にはいいねできません。');
        }

        if (!$item->favorites()->where('user_id', Auth::id())->exists()) {
            $item->favorites()->attach(Auth::id());
        }

        return back();
    }

    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->favorites()->detach(Auth::id());

        return back();
    }
}


