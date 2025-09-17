@extends('layouts.app')

@section('content')
<div class="max-w-5xl px-4 py-10 mx-auto sm:px-6 lg:px-8"
     x-data="checkoutPage({
        shippingOptionsRaw: {{ Js::from(
            $shippingOptions->map(function($o){
                return [
                    'id'            => $o->id,
                    'name'          => $o->name,
                    'price'         => (float) $o->price,
                    'country'       => (string) $o->country,
                    'cities'        => $o->cityItems->pluck('city')->values()->all(),
                    'delivery_time' => (string) ($o->delivery_time ?? ''), // <-- include ETA
                ];
            })
        ) }},
        currency: {{ Js::from(__('product.currency_aed')) }},
        // old() fallbacks (safe-serialized)
        initialCountry: {{ Js::from(old('country')) }},
        initialCity: {{ Js::from(old('city')) }},
        initialShippingId: {{ Js::from(old('shipping_option_id')) }},
        subtotal: {{ (float) $subtotal }},
        discount: {{ (float) ($coupon ? $discount : 0) }},
     })">

    {{-- Cart --}}
    <div class="grid items-start gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <h2 class="mb-4 text-xl font-semibold">{{ __('checkout.your_cart') }}</h2>

                @if (count($cart) > 0)
                    <ul class="divide-y">
                        @foreach ($cart as $id => $item)
                            <li class="flex items-center justify-between py-3">
                                <div>
                                    <p class="font-medium">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ __('checkout.qty') }}: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="font-medium">
                                    {{ __('product.currency_aed') }}
                                    {{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">{{ __('checkout.cart_empty') }}</p>
                @endif
            </div>

            @if ($errors->any())
                <div class="p-4 text-red-700 bg-red-50 rounded-xl border border-red-100">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Checkout Form --}}
            <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">
                            {{ __('checkout.full_name') }}
                        </label>
                        <input type="text" name="full_name" id="full_name" required
                               value="{{ old('full_name') }}"
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-black/20 focus:border-black/30">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            {{ __('checkout.email_address') }}
                        </label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email') }}"
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-black/20 focus:border-black/30">
                    </div>

                    <div class="col-span-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            {{ __('checkout.phone_number') }}
                        </label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-black/20 focus:border-black/30">
                    </div>
                </div>

                {{-- Country / City / Shipping --}}
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="font-semibold text-lg">{{ __('checkout.shipping_details') }}</h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        {{-- Country --}}
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('checkout.country') }}</label>
                            <select x-model="country" name="country"
                                    class="w-full p-2 border rounded-md focus:ring-black/20 focus:border-black/30">
                                <template x-for="(list, c) in countries" :key="c">
                                    <option x-text="c" :value="c"></option>
                                </template>
                            </select>
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('checkout.city') }}</label>
                            <select x-model="city" name="city"
                                    class="w-full p-2 border rounded-md focus:ring-black/20 focus:border-black/30">
                                <template x-for="ct in cities()" :key="ct">
                                    <option x-text="ct" :value="ct"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Shipping Option --}}
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('checkout.shipping_option') }}</label>
                            <select x-model="selectedShippingId"
                                    name="shipping_option_id"
                                    class="w-full p-2 border rounded-md focus:ring-black/20 focus:border-black/30"
                                    :disabled="matchingShippingOptions().length === 0">
                                <option value="">{{ __('checkout.select_shipping') }}</option>
                                <template x-for="opt in matchingShippingOptions()" :key="opt.id">
                                    <option :value="opt.id"
                                            x-text="opt.name + ' — ' + currency + ' ' + Number(opt.price).toFixed(2) + (opt.delivery_time ? ' • ' + opt.delivery_time : '')">
                                    </option>
                                </template>
                            </select>
                            {{-- Selected ETA helper --}}
                            <p class="mt-1 text-xs text-gray-500" x-show="selectedShipping()">
                                <span x-text="selectedShipping()?.delivery_time ? ('Estimated delivery: ' + selectedShipping().delivery_time) : ''"></span>
                            </p>
                            {{-- No options note --}}
                            <p class="mt-1 text-xs text-red-600" x-show="!matchingShippingOptions().length">
                                No shipping options available for this city.
                            </p>
                        </div>
                    </div>

                    {{-- Addresses --}}
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="shipping_address" class="block mb-1 text-sm font-medium text-gray-700">
                                {{ __('checkout.shipping_address') }}
                            </label>
                            <textarea name="shipping_address" id="shipping_address" required
                                      class="w-full p-2 border rounded-md focus:ring-black/20 focus:border-black/30"
                                      rows="3">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_address" class="block mb-1 text-sm font-medium text-gray-700">
                                {{ __('checkout.billing_address_optional') }}
                            </label>
                            <textarea name="billing_address" id="billing_address"
                                      class="w-full p-2 border rounded-md focus:ring-black/20 focus:border-black/30"
                                      rows="3">{{ old('billing_address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <input type="hidden" name="shipping_cost" :value="shippingCost.toFixed(2)" />
                <button type="submit"
                        class="w-full py-3 text-lg font-semibold text-white bg-black rounded-md hover:bg-black/90">
                    {{ __('checkout.confirm_pay') }}
                </button>
            </form>
        </div>

        {{-- Summary Card (sticky on desktop) --}}
        <aside class="lg:sticky lg:top-8">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <h3 class="mb-4 font-semibold text-lg">{{ __('checkout.order_summary') }}</h3>

                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt>{{ __('checkout.subtotal') }}</dt>
                        <dd x-text="currency + ' ' + Number(subtotal).toFixed(2)"></dd>
                    </div>

                    @if ($coupon)
                    <div class="flex justify-between text-green-700">
                        <dt>{{ __('checkout.coupon_discount') }}</dt>
                        <dd>- <span x-text="currency + ' ' + Number(discount).toFixed(2)"></span></dd>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <dt>{{ __('checkout.shipping') }}</dt>
                        <dd x-text="currency + ' ' + shippingCost.toFixed(2)"></dd>
                    </div>

                    <div class="flex justify-between text-gray-500" x-show="selectedShipping() && selectedShipping().delivery_time">
                        <dt>Delivery time</dt>
                        <dd x-text="selectedShipping().delivery_time"></dd>
                    </div>

                    <hr class="my-2">

                    <div class="flex justify-between font-semibold text-base">
                        <dt>{{ __('checkout.total') }}</dt>
                        <dd x-text="currency + ' ' + total().toFixed(2)"></dd>
                    </div>
                </dl>
            </div>
        </aside>
    </div>
</div>

{{-- Alpine controller --}}
<script>
function checkoutPage({ shippingOptionsRaw, currency, initialCountry, initialCity, initialShippingId, subtotal, discount }) {
    // Build countries map from shipping options (country -> unique cities)
    const countries = shippingOptionsRaw.reduce((acc, opt) => {
        const key = (opt.country || '').trim();
        if (!key) return acc;
        acc[key] = acc[key] || [];
        (opt.cities || []).forEach(c => {
            if (c && !acc[key].includes(c)) acc[key].push(c);
        });
        return acc;
    }, {});

    const firstCountry = Object.keys(countries)[0] || '';
    const firstCity    = (countries[initialCountry] || countries[firstCountry] || [])[0] || '';

    return {
        // data
        currency,
        shippingOptions: shippingOptionsRaw,
        countries,
        country: (initialCountry && countries[initialCountry]) ? initialCountry : firstCountry,
        city:     initialCity || firstCity,
        selectedShippingId: initialShippingId || null,
        shippingCost: 0,
        subtotal: Number(subtotal) || 0,
        discount: Number(discount) || 0,

        // derived
        cities() {
            return this.countries[this.country] || [];
        },
        matchingShippingOptions() {
            return this.shippingOptions.filter(opt =>
                opt.country === this.country &&
                Array.isArray(opt.cities) &&
                opt.cities.includes(this.city)
            );
        },
        selectedShipping() {
            return this.shippingOptions.find(opt => opt.id == this.selectedShippingId) || null;
        },
        calculateShipping() {
            const selected = this.selectedShipping();
            this.shippingCost = selected ? Number(selected.price) : 0;
        },
        total() {
            return Math.max(0, this.subtotal - this.discount) + this.shippingCost;
        },

        // helpers
        bestShippingId() {
            const opts = this.matchingShippingOptions();
            if (!opts.length) return null;
            // pick the cheapest by price
            let best = opts[0];
            for (let i = 1; i < opts.length; i++) {
                if (Number(opts[i].price) < Number(best.price)) best = opts[i];
            }
            return best.id;
        },
        ensureSelectedShippingValid() {
            const ids = this.matchingShippingOptions().map(o => o.id);
            if (ids.includes(this.selectedShippingId)) return;
            this.selectedShippingId = this.bestShippingId();
        },

        // lifecycle
        init() {
            // Ensure city is valid for the selected country
            if (!this.city || !this.cities().includes(this.city)) {
                this.city = this.cities()[0] || '';
            }

            // If initial saved option is valid keep it; otherwise choose best (cheapest)
            this.ensureSelectedShippingValid();
            this.calculateShipping();

            // Reactive updates
            this.$watch('country', () => {
                this.city = this.cities()[0] || '';
                this.ensureSelectedShippingValid();
                this.calculateShipping();
            });
            this.$watch('city', () => {
                this.ensureSelectedShippingValid();
                this.calculateShipping();
            });
            this.$watch('selectedShippingId', () => this.calculateShipping());
        }
    }
}
</script>
@endsection
