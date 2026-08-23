<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_picture' => ['nullable', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'availability' => ['required', 'boolean'],
        ]);

        $validated['sku'] = $this->generateSku($request);

        if ($request->hasFile('product_picture')) {
            $validated['product_picture'] = $request->file('product_picture')->store('products', 'public');
        }

        $request->user()->products()->create($validated);

        return Redirect::route('dashboard')->with('status', 'Product created successfully.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product = $request->user()->products()->findOrFail($product->id);

        $validated = $request->validate([
            'product_picture' => ['nullable', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'availability' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('product_picture')) {
            if ($product->product_picture) {
                Storage::disk('public')->delete($product->product_picture);
            }

            $validated['product_picture'] = $request->file('product_picture')->store('products', 'public');
        }

        $product->update($validated);

        return Redirect::route('dashboard')->with('status', 'Product updated successfully.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $product = $request->user()->products()->findOrFail($product->id);

        if ($product->product_picture) {
            Storage::disk('public')->delete($product->product_picture);
        }

        $product->delete();

        return Redirect::route('dashboard')->with('status', 'Product deleted successfully.');
    }

    private function generateSku(Request $request): string
    {
        do {
            $sku = 'SKU-'.Str::upper(Str::random(8));
        } while ($request->user()->products()->where('sku', $sku)->exists());

        return $sku;
    }
}