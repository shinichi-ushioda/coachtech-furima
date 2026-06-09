<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();

        $page = $request->input('page', 'sell');

        if ($page === 'buy') {

            $purchasedItemIds = Order::where('user_id', $user->id)->pluck('item_id');
            $items = Item::whereIn('id', $purchasedItemIds)->get();
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('users.profile', compact('user', 'page', 'items'));
    }
    
    public function editProfile()
    {
        $user = Auth::user();
        return view('users.edit_profile', compact('user'));
    }

    
    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        if ($request->hasFile('img_url')) {

            if ($user->img_url) {
                Storage::disk('public')->delete($user->img_url);
            }

            $path = $request->file('img_url')->store('profiles', 'public');
            $user->img_url = $path;
        }

        $user->save();

        return redirect('/');
    }
}
