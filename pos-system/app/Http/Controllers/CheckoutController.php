<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        $total = 0;

        foreach ($cart as $item) {
            $qty = $item['qty'] ?? 1;
            $total += $item['price'] * $qty;
        }
        session()->forget('cart');

        return redirect()->back()->with([
            'receipt' => $cart,
            'total' => $total
        ]);
    }
}