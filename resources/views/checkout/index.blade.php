@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-10 mx-auto sm:px-6 lg:px-8"
     x-data="{
        country: '{{ old('country', array_key_first($countries)) }}',
        city: '{{ old('city') }}',
        shippingCost: 0,
        selectedShippingId: null,
        countries: {{ Js::from($countries) }},
        shippingOptions: {{ Js::from($shippingOptions) }},
        currency: '{{ __('product.currency_aed') }}',

        cities() { return this.countries[this.country] || []; },
        matchingShippingOptions() {
            return this.shippingOptions.filter(opt => opt.country === this.country && opt.city === this.city);
        },
        calculateShipping() {
            const selected = this.shippingOptions.find(opt => opt.id == this.selectedShippingId);
            this.shippingCost = selected ? parseFloat(selected.price) : 0;
        },
        init() {
            if (!this.city && this.cities().length > 0) this.city = this.cities()[0];

            const match = this.matchingShippingOptions()[0];
            if (match) { this.selectedShippingId = match.id; this.shippingCost = parseFloat(match.price); }

            this.$watch('country', () => {
                this.city = this.cities()[0] || '';
                const m = this.matchingShippingOptions()[0];
                this.selectedShippingId = m ? m.id : null;
                this.shippingCost = m ? parseFloat(m.price) : 0;
            });

            this.$watch('city', () => {
                const m = this.matchingShippingOptions()[0];
                this.selectedShippingId = m ? m.id : null;
                this.shippingCost = m ? parseFloat(m.price) : 0;
            });

            this.$watch('selectedShippingId', () => this.calculateShipping());
        }
     }">
    {{-- Cart Items --}}
    <div class="p-6 mb-6 bg-white rounded shadow">
        <h2 class="mb-4 text-xl font-semibold">{{ __('checkout.your_cart') }}</h2>

        @if (count($cart) > 0)
            <ul class="divide-y">
                @foreach ($cart as $id => $item)
                    <li class="flex justify-between py-3">
                        <div>
                            <p class="font-medium">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ __('checkout.qty') }}: {{ $item['quantity'] }}</p>
                        </div>
                        <div>{{ __('product.currency_aed') }} {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">{{ __('checkout.cart_empty') }}</p>
        @endif
    </div>

    @if ($errors->any())
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Checkout Form --}}
    <form method="POST" action="{{ route('checkout.process') }}" class="space-y-4">
        @csrf

        @if (!auth()->check())
            <div class="mb-4">
                <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('checkout.full_name') }}
                </label>
                <input type="text" name="full_name" id="full_name" required
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="{{ __('checkout.full_name_placeholder') }}">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('checkout.email_address') }}
                </label>
                <input type="email" name="email" id="email" required
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="{{ __('checkout.email_placeholder') }}">
            </div>
        @endif

        {{-- Country / City --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-1 font-medium">{{ __('checkout.country') }}</label>
                <select x-model="country" name="country" class="w-full p-2 border rounded">
                    <template x-for="(cities, c) in countries" :key="c">
                        <option x-text="c" :value="c"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">{{ __('checkout.city') }}</label>
                <select x-model="city" name="city" class="w-full p-2 border rounded">
                    <template x-for="ct in cities()" :key="ct">
                        <option x-text="ct" :value="ct"></option>
                    </template>
                </select>
            </div>
        </div>

        {{-- Shipping Option --}}
        <div>
            <label class="block mb-1 font-medium">{{ __('checkout.shipping_option') }}</label>
            <select x-model="selectedShippingId" name="shipping_option_id" class="w-full p-2 border rounded">
                <option value="" disabled>{{ __('checkout.select_shipping') }}</option>
                <template x-for="opt in matchingShippingOptions()" :key="opt.id">
                    <option :value="opt.id"
                            x-text="opt.name + ' - ' + currency + ' ' + parseFloat(opt.price).toFixed(2)">
                    </option>
                </template>
            </select>
        </div>

        {{-- Addresses --}}
        <div>
            <label for="shipping_address" class="block mb-1 font-medium">{{ __('checkout.shipping_address') }}</label>
            <textarea name="shipping_address" id="shipping_address" class="w-full p-2 border rounded" required>{{ old('shipping_address') }}</textarea>
            @error('shipping_address')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="billing_address" class="block mb-1 font-medium">{{ __('checkout.billing_address_optional') }}</label>
            <textarea name="billing_address" id="billing_address" class="w-full p-2 border rounded">{{ old('billing_address') }}</textarea>
        </div>

        {{-- Order Summary --}}
        <div class="p-4 mt-4 bg-gray-100 border rounded">
            <p class="mb-2">
                {{ __('checkout.subtotal') }}: {{ __('product.currency_aed') }} {{ number_format($subtotal, 2) }}
            </p>

            @if ($coupon)
                <p class="mb-2 text-green-600">
                    {{ __('checkout.coupon_discount') }}: -{{ __('product.currency_aed') }} {{ number_format($discount, 2) }}
                </p>
            @endif

            <p class="mb-2">
                {{ __('checkout.shipping') }}:
                <span x-text="currency + ' ' + shippingCost.toFixed(2)"></span>
            </p>

            <p class="font-bold">
                {{ __('checkout.total') }}:
                <span x-text="currency + ' ' + ({{ $subtotal - $discount }} + shippingCost).toFixed(2)"></span>
            </p>
        </div>

        <input type="hidden" name="shipping_cost" :value="shippingCost.toFixed(2)" />

        <button type="submit"
                class="w-full py-3 text-lg font-semibold text-white bg-green-600 rounded hover:bg-green-700">
            {{ __('checkout.confirm_pay') }}
        </button>
    </form>
</div>
@endsection
