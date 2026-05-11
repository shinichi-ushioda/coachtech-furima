<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('layouts.app');
    }

    public function store(Request $request)
    {
        //画像保存(FN029)
        $path = $request->file('image')->store('products','public');

        //商品情報保存(FN028)
        $item = new Item();
        $item->name = $request->name;
        $item->brand_name = $request->brand_name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->image_url = $path; //画像のパスを保存
        $item->user_id = auth()->id(); // 出品者のユーザーIDを保存
        $item->save();

        return redirect()->route('items.index');
    }
}