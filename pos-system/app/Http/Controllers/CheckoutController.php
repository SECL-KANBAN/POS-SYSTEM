<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function store(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Cart is empty');
    }

    $total = 0;

    foreach ($cart as $item) {
        $qty = $item['quantity'] ?? 1;

        $product = Product::find($item['id']);

        if ($product) {

            $product->stock -= $qty;

            if ($product->stock <= 0) {
                $product->stock = 0;
                $product->availability = 0;
            }

            $product->save();
        }

        $total += $item['price'] * $qty;
    }

    session()->forget('cart');

    return view('receipt', [
        'cart' => $cart,
        'total' => $total
    ]);
}
}
