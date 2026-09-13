<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::get('/', function () {
        return redirect()->route('register');
    });

});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'products' => request()->user()->products()->latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/transaction-history', function () {
    return view('transaction-history', [
        //'transactions' => request()->user()->transactions()->latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('transaction-history');

Route::middleware('auth')->group(function () {

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/cart/add/{product}', [ProductController::class, 'addToCart'])
    ->name('cart.add');

    Route::post('/cart/decrease/{product}', [ProductController::class, 'decreaseCart'])
    ->name('cart.decrease');
   
   Route::get('/checkout', function () {
    $cart = session()->get('cart', []);
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('checkout', [
        'cart' => $cart,
        'total' => $total,
    ]);
})->name('checkout');

Route::post('/checkout', function (\Illuminate\Http\Request $request) {
    $cart = session()->get('cart', []);
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $request->validate([
        'amount_paid' => ['required', 'numeric', 'min:' . $total],
    ]);

    session()->forget('cart');

    return redirect()->route('dashboard')
        ->with('status', __('Payment received. Change: ₱:change', [
            'change' => number_format($request->amount_paid - $total, 2),
        ]));
})->name('checkout.store');
});

require __DIR__.'/auth.php';