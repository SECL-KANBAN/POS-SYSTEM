<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800"
                 x-data="{ amountPaid: {{ old('amount_paid', $total) }}, total: {{ $total }} }">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h2 class="text-xl font-semibold">{{ __('Checkout') }}</h2>

                    @if ($errors->any())
                        <p class="mt-4 text-sm text-red-600 dark:text-red-400">
                            {{ $errors->first() }}
                        </p>
                    @endif

                    @if (count($cart) > 0)

                        <div class="mt-6 space-y-3">
                            @foreach ($cart as $item)
                                <div class="flex items-center justify-between rounded-md border border-gray-200 p-3 dark:border-gray-700">
                                    <div>
                                        <p class="font-medium">{{ $item['name'] }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item['quantity'] }} × ₱{{ number_format($item['price'], 2) }}
                                        </p>
                                    </div>
                                    <div class="font-semibold">
                                        ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="amount_paid" :value="__('Amount Paid')" />
                                <x-text-input
                                    id="amount_paid"
                                    name="amount_paid"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    x-model.number="amountPaid"
                                    required
                                />
                                <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                            </div>

                            <div class="flex justify-between text-base font-semibold">
                                <span>{{ __('Change') }}</span>
                                <span
                                    :class="(amountPaid - total) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                    x-text="(amountPaid - total) >= 0 ? ('₱' + (amountPaid - total).toFixed(2)) : 'Insufficient amount'"
                                ></span>
                            </div>

                            <div class="flex items-center justify-between gap-3 pt-2">
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    {{ __('Back to cart') }}
                                </a>
                                <x-primary-button type="submit">
                                    {{ __('Confirm Payment') }}
                                </x-primary-button>
                            </div>
                        </form>

                    @else

                        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Your cart is empty.') }}
                        </p>
                        <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('Return to dashboard') }}
                        </a>

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>