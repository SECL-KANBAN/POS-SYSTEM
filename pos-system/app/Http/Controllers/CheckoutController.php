<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // kunin cart (session or DB)
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // save order (optional)
        // Order::create([...]);

        // clear cart
        session()->forget('cart');

        return view('receipt', compact('cart', 'total'));
    }
}