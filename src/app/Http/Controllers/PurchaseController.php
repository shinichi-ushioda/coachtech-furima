<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = Auth::user();

        $payment_methods = [
            'konbini' => 'コンビニ払い',
            'card'        => 'カード支払い',
        ];

        return view('items.purchase', compact('item', 'user', 'payment_methods'));
    }

    public function editAddress($item_id)
    {
        $user = auth()->user();

         return view('users.address_edit', compact('user', 'item_id'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
        ]);

        $user = auth()->user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('items.purchase', ['item_id' => $item_id]);
    }

    public function success(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $shippingAddress = "〒{$user->postal_code} {$user->address} {$user->building}";

        try {
            DB::transaction(function () use ($item, $user, $shippingAddress) {
                $item->update(['is_sold' => true]);

                Order::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'stripe_payment_intent_id' => 'paid_via_stripe_' . uniqid(),
                    'status' => 'paid',
                    'shipping_address' => $shippingAddress,
                ]);
            });

            return redirect()->route('items.index')->with('success', '購入が完了しました！');

        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', '購入確定処理に失敗しました。');
        }
    }

    public function cancel($item_id)
    {
        return redirect()->route('items.show', ['item_id' => $item_id])->with('error', '決済がキャンセルされました。');
    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        
        $item = Item::findOrFail($item_id);

        $paymentMethod = $request->input('payment_method');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'success_url' => route('purchase.success', ['item_id' => $item->id]). '?method=' . $paymentMethod,
                'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]), 
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price, 
                    ],
                    'quantity' => 1,
                ]],
                'payment_method_types' => [
                    $paymentMethod
                ],
            ]);

            return redirect()->away($session->url);

        } catch (\Exception $e) {
             return redirect()->back()->with('error', '決済画面の起動に失敗しました：' . $e->getMessage());
        }
    }
}
