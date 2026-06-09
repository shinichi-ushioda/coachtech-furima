<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
   public function index(Request $request)
   {
    if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    $search = $request->input('search');
    $page = $request->input('page', 'recommend');


    $query = Item::query();

    $query->keyword($search);

    if ($page === 'mylist') {

        if (Auth::check()) {

            $current_user_id = Auth::id();
            $query->whereHas('favorites', function ($q) use ($current_user_id) {
                $q->where('user_id', $current_user_id);
            });
        } else {
            $query->whereNull('id');
        }
    } else {
        $current_user_id = Auth::id() ?? 999;
        $query->where('user_id', '!=', $current_user_id);
    }

    $items = $query->get();

    return view('items.index', compact('items', 'search', 'page'));
   }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.listing', compact('categories', 'conditions'));
    }


    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('products','public');
        $item = new Item();
        $item->name = $request->name;
        $item->brand_name = $request->brand_name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->img_url = $path;
        $item->user_id = auth()->id();
        $item->condition_id = $request->condition_id; 
        $item->save();

        if ($request->has('categories')) {
            $item->categories()->attach($request->categories);
        }

        return redirect()->route('items.index');
    }

    public function show($item_id)
    {
        $item = Item::with(['categories', 'favorites', 'comments.user', 'condition'])->findOrFail($item_id);
        $is_favorited = false;
        if (Auth::check()) {
            $is_favorited = $item->favorites->contains(Auth::id());
        }
        return view('items.show', compact('item', 'is_favorited'));
    }

}
