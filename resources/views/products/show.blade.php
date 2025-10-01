@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-3" x-data="{ showModal: false, modalImage: '' }">
        <div
            x-data="productPage({
                images: @js(
                    $product->images
                        ->map(fn($img) => [
                            'src' => asset('storage/'.$img->image_path),
                            'alt' => $img->alt_text ?? __('product.image_alt'),
                        ])
                        ->values()
                ),
                stockQty: {{ (int) ($product->stock_quantity ?? 0) }},
            })"
            class="grid grid-cols-1 gap-16 lg:gap-8 md:grid-cols-2 place-items-center"
        >
            {{-- Left Panel (Carousel) --}}
            <div class="w-full h-full">
                <div class="relative w-full rounded-lg overflow-hidden min-h-96">
                    <template x-for="(img, i) in images" :key="i">
                        <div x-show="index === i" x-transition.opacity.duration.300ms
                             class="absolute inset-0 flex items-center justify-center justify-self-center w-fit"
                             @mouseenter="paused = true; hover = true"
                             @mouseleave="paused = false; hover = false; originX = 50; originY = 50"
                             @mousemove="onMove($event)">
                            <img :src="img.src" :alt="img.alt"
                                 class="max-w-full max-h-full object-contain select-none pointer-events-none rounded"
                                 draggable="false" :style="imgStyle(i)">
                        </div>
                    </template>
                </div>

                <!-- Controls -->
                <div class="flex items-center justify-center gap-6 mt-3">
                    <button @click="prev()"
                            class="bg-black/70 text-white rounded-full w-8 h-8 flex justify-center items-center hover:bg-black">
                        &#10094;
                    </button>

                    <div class="flex items-center gap-2">
                        <template x-for="(img, i) in images" :key="'dot_'+i">
                            <button @click="go(i)"
                                    class="w-2.5 h-2.5 rounded-full transition"
                                    :class="i === index ? 'bg-black' : 'bg-gray-400'"></button>
                        </template>
                    </div>

                    <button @click="next()"
                            class="bg-black/70 text-white rounded-full w-8 h-8 flex justify-center items-center hover:bg-black">
                        &#10095;
                    </button>
                </div>
            </div>

            {{-- Right panel --}}
            <div class="w-full px-5">
                <h1 class="mb-2 text-3xl montaga-semibold text-charcoal">
                    {{ app()->getLocale() === 'ar' && $product->name_ar ? $product->name_ar : $product->name }}
                </h1>

                {{-- Price (with optional discount) --}}
                @php
                    $hasPromo = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                @endphp
                <div class="mb-4 flex items-center gap-3">
                    @if($hasPromo)
                        <span class="text-2xl font-semibold text-amber-700">
                            {{ __('product.currency_aed') }} {{ number_format($product->discount_price, 2) }}
                        </span>
                        <span class="text-lg text-gray-500 line-through">
                            {{ __('product.currency_aed') }} {{ number_format($product->price, 2) }}
                        </span>
                        @php
                            $percent = round((($product->price - $product->discount_price) / $product->price) * 100);
                        @endphp
                        <span class="text-[11px] px-1.5 py-0.5 font-bold text-white bg-emerald-600 rounded-md">
                            -{{ $percent }}%
                        </span>
                    @else
                        <span class="text-2xl font-semibold text-amber-800">
                            {{ __('product.currency_aed') }} {{ number_format($product->price, 2) }}
                        </span>
                    @endif
                </div>

                @if ($product->description)
                    <h2 class="mb-2 text-2xl font-bold text-charcoal">{{ __('product.description') }}</h2>
                    <div class="prose prose-sm max-w-none">
                        {!! app()->getLocale() === 'ar' && $product->description_ar ? $product->description_ar : $product->description !!}
                    </div>
                @endif

                {{-- Add to cart (stock_quantity-based) --}}
                <form action="{{ route('cart.add', ['id' => $product->id]) }}" method="POST" class="mt-6">
                    @csrf

                    {{-- Quantity --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('product.quantity') }}</label>
                        <div class="flex items-center rounded-md overflow-hidden w-28">
                            <button type="button" @click="if(qty > 1) qty--" :disabled="qty <= 1"
                                    class="w-9 py-1 bg-gray-200 hover:bg-gray-300 rounded-l disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-icons text-lg">remove</span>
                            </button>

                            <input type="text" readonly name="quantity" :value="qty"
                                   class="w-10 text-center border-y border-gray-300 focus:ring-0 focus:outline-none py-1">

                            <button type="button" @click="if(qty < maxQty) qty++" :disabled="qty >= maxQty"
                                    class="w-9 py-1 bg-gray-200 hover:bg-gray-300 rounded-r disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-icons text-lg">add</span>
                            </button>
                        </div>
                        <p class="mt-2 text-xs" :class="maxQty > 0 ? 'text-gray-500' : 'text-red-600'">
                            <span x-show="maxQty > 0">{{ __('product.available') }}: <span x-text="maxQty"></span></span>
                            <span x-show="maxQty === 0">{{ __('product.out_of_stock') }}</span>
                        </p>
                    </div>

                    {{-- Pill with subtle bounce (gold theme) --}}
                    <button type="submit" :disabled="maxQty === 0"
                            class="inline-flex items-center justify-center px-5 py-2 rounded-full
                                   text-white font-medium
                                   transition-transform duration-200
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   bg-amber-700 hover:bg-amber-800
                                   hover:-translate-y-0.5">
                        <span class="material-icons mr-2">add_shopping_cart</span>
                        {{ __('product.add_to_cart') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- More like this --}}
        <div>
            @if ($smiliarProducts->isNotEmpty())
                <h1 class="text-3xl montaga-regular m-6 mt-14">{{ __('product.more_like_this') }}</h1>
            @endif

            <div class="flex flex-wrap gap-5 m-5">
                @foreach ($smiliarProducts as $sp)
                    <div class="overflow-hidden transition bg-white rounded-lg shadow-md duration-500 hover:shadow-2xl fade-up w-[250px]">
                        @if ($sp->images->count())
                            <div class="relative flex justify-center items-center w-full h-[300px] overflow-hidden rounded-t-md p-1">
                                <img class="h-full object-contain cursor-zoom-in rounded-md"
                                     alt="{{ $sp->name }}"
                                     src="{{ asset('storage/' . $sp->images->first()->image_path) }}"
                                     @click="showModal = true; modalImage = '{{ asset('storage/' . $sp->images->first()->image_path) }}'">
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-charcoal">
                                {{ app()->getLocale() === 'ar' && $sp->name_ar ? $sp->name_ar : $sp->name }}
                            </h3>
                            <p class="mt-1 text-gray-600">
                                {{ \Illuminate\Support\Str::limit(app()->getLocale() === 'ar' && $sp->description_ar ? $sp->description_ar : $sp->description, 80) }}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xl font-bold text-amber-700">
                                    {{ __('product.currency_aed') }} {{ number_format($sp->price, 2) }}
                                </span>
                                <a href="{{ route('products.show', $sp->slug) }}"
                                   class="inline-flex items-center justify-center px-5 py-2 rounded-full
                                          text-white font-medium
                                          bg-amber-700 hover:bg-amber-800
                                          transition-transform duration-200 hover:-translate-y-0.5">
                                    {{ __('product.view') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Image Zoom Modal --}}
        <div x-cloak x-show="showModal" x-transition
             class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="showModal = false" role="dialog">
            <div class="relative max-w-full max-h-screen">
                <img @click.outside="showModal = false" :src="modalImage"
                     class="max-w-full max-h-[90vh] rounded shadow-xl" alt="">
            </div>
        </div>
    </div>

    <script>
        function productPage(init) {
            return {
                images: Array.isArray(init.images) ? init.images : [],
                index: 0,
                paused: false,
                hover: false,
                zoom: 2,
                originX: 50,
                originY: 50,
                intervalId: null,

                // stock/qty (no variants)
                maxQty: Number.isFinite(init.stockQty) ? init.stockQty : 0,
                qty: 1,

                // carousel
                start() {
                    this.stop();
                    if (this.images.length <= 1) return;
                    this.intervalId = setInterval(() => { if (!this.paused) this.next(); }, 5000);
                },
                stop() { if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; } },
                next() { this.index = (this.index + 1) % this.images.length; },
                prev() { this.index = (this.index - 1 + this.images.length) % this.images.length; },
                go(i) { this.index = i; },
                onMove(e) {
                    const r = e.currentTarget.getBoundingClientRect();
                    this.originX = Math.max(0, Math.min(100, ((e.clientX - r.left) / r.width) * 100));
                    this.originY = Math.max(0, Math.min(100, ((e.clientY - r.top) / r.height) * 100));
                },
                imgStyle(i) {
                    const active = this.index === i;
                    const scale = this.hover && active ? this.zoom : 1;
                    return `transform-origin:${this.originX}% ${this.originY}%; transform:scale(${scale}); transition: transform 120ms ease;`;
                },
            }
        }
    </script>
@endsection
