<x-app-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex min-h-[calc(100vh-10rem)] gap-6">
                <div class="min-w-0 flex-1 overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800" x-data="{ showProductForm: {{ $errors->any() ? 'true' : 'false' }}, editProduct: null }" @keydown.escape.window="showProductForm = false; editProduct = null">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-semibold">{{ __('Products') }}</h2>

                        @if (session('status'))
                            <p class="mt-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
                        @endif

                        <x-primary-button class="mt-4" type="button" x-on:click="showProductForm = !showProductForm">
                            {{ __('Add Product') }}
                        </x-primary-button>

                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4" x-show="showProductForm" x-cloak x-transition.opacity @click.self="showProductForm = false">
                            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800" role="dialog" aria-modal="true" aria-labelledby="add-product-title">
                                <div class="flex items-center justify-between">
                                    <h3 id="add-product-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Add Product') }}</h3>
                                    <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="{{ __('Close') }}" x-on:click="showProductForm = false">&times;</button>
                                </div>

                                <form class="mt-6 space-y-4" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div>
                                        <x-input-label for="product_picture" :value="__('ProductPicture')" />
                                        <input id="product_picture" name="product_picture" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400" />
                                        <x-input-error :messages="$errors->get('product_picture')" class="mt-2" />
                                    </div>


                                    <div>
                                        <x-input-label for="name" :value="__('Name')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('SKU will be generated automatically.') }}</p>

                                    <div>
                                        <x-input-label for="price" :value="__('Price')" />
                                        <x-text-input id="price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" :value="old('price')" required />
                                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="stock" :value="__('Stock')" />
                                        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock')" required />
                                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="availability" :value="__('Availability')" />
                                        <select id="availability" name="availability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                            <option value="1" @selected(old('availability', '1') === '1')>{{ __('Available') }}</option>
                                            <option value="0" @selected(old('availability') === '0')>{{ __('Unavailable') }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('availability')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center justify-end gap-3">
                                        <x-secondary-button type="button" x-on:click="showProductForm = false">{{ __('Cancel') }}</x-secondary-button>
                                        <x-primary-button>{{ __('Save Product') }}</x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="mt-8 space-y-3">

                            @forelse ($products as $product)
                                <div class="flex items-center gap-3 rounded-md border border-gray-200 p-3 dark:border-gray-700">
                                    @if ($product->product_picture)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->product_picture) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover" />
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->sku }} · ₱{{ number_format((float) $product->price, 2) }} · {{ $product->stock }} in stock</p>
                                    </div>
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <span class="text-sm {{ $product->availability ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $product->availability ? __('Available') : __('Unavailable') }}
                                        </span>
                                        <x-secondary-button class="!h-8 !w-8 !px-0 !py-0 justify-center text-base" type="button" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}" x-on:click="editProduct = {{ $product->id }}">
                                            <i class="fi fi-rr-edit" aria-hidden="true"></i>
                                        </x-secondary-button>

                                        <form method="POST" action="{{ route('cart.add', $product) }}">
                                            @csrf

                                            <x-secondary-button
                                                class="!h-8 !w-8 !px-0 !py-0 justify-center text-base"
                                                type="submit"
                                                title="{{ __('Add to Cart') }}"
                                                aria-label="{{ __('Add to Cart') }}"
                                            >
                                                <i class="fi fi-rr-shopping-cart-add" aria-hidden="true"></i>
                                            </x-secondary-button>
                                        </form>

                                        <form class="shrink-0" method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('Delete this product?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button class="!h-8 !w-8 !px-0 !py-0 justify-center text-base" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}">
                                                <i class="fi fi-rr-trash" aria-hidden="true"></i>
                                            </x-danger-button>
                                        </form>
                                    </div>
                                </div>

                                <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4" x-show="editProduct === {{ $product->id }}" x-cloak x-transition.opacity @click.self="editProduct = null">
                                    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800" role="dialog" aria-modal="true" aria-labelledby="edit-product-title-{{ $product->id }}">
                                        <div class="flex items-center justify-between">
                                            <h3 id="edit-product-title-{{ $product->id }}" class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Edit Product') }}</h3>
                                            <button type="button" class="text-2xl leading-none text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="{{ __('Close') }}" x-on:click="editProduct = null">&times;</button>
                                        </div>

                                        <form class="mt-6 space-y-4" method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <x-input-label for="edit_product_picture_{{ $product->id }}" :value="__('ProductPicture')" />
                                                <input id="edit_product_picture_{{ $product->id }}" name="product_picture" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400" />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_name_{{ $product->id }}" :value="__('Name')" />
                                                <x-text-input id="edit_name_{{ $product->id }}" name="name" type="text" class="mt-1 block w-full" value="{{ $product->name }}" required />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_price_{{ $product->id }}" :value="__('Price')" />
                                                <x-text-input id="edit_price_{{ $product->id }}" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" value="{{ $product->price }}" required />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_stock_{{ $product->id }}" :value="__('Stock')" />
                                                <x-text-input id="edit_stock_{{ $product->id }}" name="stock" type="number" min="0" class="mt-1 block w-full" value="{{ $product->stock }}" required />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_availability_{{ $product->id }}" :value="__('Availability')" />
                                                <select id="edit_availability_{{ $product->id }}" name="availability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                                    <option value="1" @selected($product->availability)>{{ __('Available') }}</option>
                                                    <option value="0" @selected(! $product->availability)>{{ __('Unavailable') }}</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center justify-end gap-3">
                                                <x-secondary-button type="button" x-on:click="editProduct = null">{{ __('Cancel') }}</x-secondary-button>
                                                <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No products created yet.') }}</p>
                            @endforelse
                        </div>

                    </div>
                </div>

                <div class="min-w-0 flex-1 overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <h2 class="text-xl font-semibold">{{ __('Cart') }}</h2>

                @php
                    $cart = session()->get('cart', []);
                @endphp

                @if(count($cart) > 0)

                    <div class="mt-6 space-y-3">

                        @foreach($cart as $item)

                            <div class="flex items-center gap-3 rounded-md border border-gray-200 p-3 dark:border-gray-700">

                                {{-- Product Image --}}
                                @if(!empty($item['image']))
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}"
                                        alt="{{ $item['name'] }}"
                                        class="h-12 w-12 rounded object-cover"
                                    >
                                @endif

                                {{-- Product Information --}}
                                <div class="min-w-0 flex-1">

                                    <p class="font-medium">
                                        {{ $item['name'] }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $item['sku'] }}
                            </p>

                            <div class="mt-2 flex items-center gap-2">

                            {{-- Minus Button --}}
                            <form method="POST" action="{{ route('cart.decrease', $item['id']) }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 text-sm font-semibold hover:bg-gray-100"
                                >
                                    −
                                </button>
                            </form>

                            {{-- Quantity --}}
                            <span class="min-w-[25px] text-center font-medium">
                                {{ $item['quantity'] }}
                            </span>

                            {{-- Plus Button --}}
                            <form method="POST" action="{{ route('cart.add', $item['id']) }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 text-sm font-semibold hover:bg-gray-100"
                                >
                                    +
                                </button>
                            </form>

                        </div>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            ₱{{ number_format($item['price'], 2) }} each
                        </p>

                        </div>

                        {{-- Item Total --}}
                        <div class="font-semibold">
                            ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>

                    </div>

                @endforeach

                                </div>

                                @php
                                    $total = 0;

                                    foreach($cart as $item) {
                                        $total += $item['price'] * $item['quantity'];
                                    }
                                @endphp

                                <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">

                                    <div class="flex justify-between text-lg font-semibold">

                                        <span>Total</span>

                                        <span>
                                            ₱{{ number_format($total, 2) }}
                                        </span>

                                    </div>

                                </div>

                            @else

                                <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                                    Your cart is empty.
                                </p>

                            @endif
                            <div style="margin-top: 20px; text-align: right; ">
                            <a id="checkoutBtn" href="{{ route('checkout') }}" 
                            style="background: #ffffff; 
                                color: #1F2937; 
                                padding: 6px 18px; 
                                border-radius: 6px; 
                                text-decoration: none;
                                display: inline-block;
                                font-family: Arial;">
                            Checkout
                        </a>
                    </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
