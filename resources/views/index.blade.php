@extends('layouts.app')

@push('head')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
@endpush

@section('content')
    {{-- Hero with gold theme --}}
    <div class="h-[40vh] md:h-[60vh] mx-6 rounded-3xl bg-cover bg-center bg-no-repeat md:bg-fixed"
        style="background-image:url('{{ asset('images/hero-bg.jpg') }}');">
        <div
            class="relative bg-black/30 rounded-3xl z-10 h-full flex flex-col items-center justify-evenly p-4 text-center space-y-2">
            <h1 class="text-2xl md:text-6xl font-extrabold montserrat-bold leading-tight text-white">
                <span class="inline box-decoration-clone rounded-lg px-2 md:px-3 py-0.5 md:py-1">
                    {{ __('shop.featured_products') }}
                </span>
            </h1>

            <h3 class="text-xl md:text-5xl font-bold text-white/95" style="font-family:'Dancing Script', cursive;">
                <span
                    class="inline box-decoration-clone rounded-lg px-2 md:px-3 py-0.5 md:py-1
                              bg-amber-900/20 backdrop-blur-md ring-1 ring-white/20">
                    {{ __('shop.discover_elegance') }}
                </span>
            </h3>

            <p class="max-w-xl font-serif text-sm md:text-base text-white/90 leading-relaxed">
                <span class="inline box-decoration-clone rounded-md px-2 py-0.5 font-serif text-base">
                    {{ __('shop.handcrafted_selection') }}
                </span>
            </p>
        </div>
    </div>

    @php
        // Flatten grouped $products as in your second snippet
        $productsFlat = collect($products)
            ->flatMap(function ($group, $label) {
                return collect($group)->map(function ($p) use ($label) {
                    return [
                        'id' => $p->id,
                        'slug' => $p->slug,
                        'name' => app()->getLocale() === 'ar' && $p->name_ar ? $p->name_ar : $p->name,
                        'description' =>
                            app()->getLocale() === 'ar' && $p->description_ar ? $p->description_ar : $p->description,
                        'price' => (float) $p->price,
                        'discount_price' => $p->discount_price ? (float) $p->discount_price : null,
                        'images' => $p->images->take(3)->map(fn($i) => ['path' => $i->image_path])->values(),
                        'category_label' => $label,
                        'category_id' => $p->category_id,
                        'sizes' => $p->sizes?->pluck('size')->filter()->values()->all() ?? [],
                    ];
                });
            })
            ->values();

        $categoryOptions = collect($products)
            ->map(
                fn($group, $label) => [
                    'id' => optional($group->first())->category_id,
                    'label' => $label,
                ],
            )
            ->filter(fn($c) => !is_null($c['id']))
            ->values();

        $sizeOptions = collect($productsFlat)->pluck('sizes')->flatten()->filter()->unique()->sort()->values();
    @endphp

    <div x-data="page(
        @js($productsFlat),
        @js($categoryOptions),
        @js(asset('storage')),
        @js($sizeOptions)
    )" class="relative min-h-screen bg-neutral-50">

        {{-- Floating Filter + Wishlist (gold gradient) --}}
        <div class="flex justify-evenly gap-4 fixed bottom-4 right-4 z-40">
            <button @click="drawerOpen = true"
                class="px-5 py-3 rounded-full shadow-lg
                           bg-gradient-to-r from-black via-amber-700 to-black
                           text-white font-medium
                           bg-[length:200%_100%] bg-left hover:bg-right transition-all duration-500">
                {{ __('shop.filter') }}
            </button>
        </div>

        {{-- Slide-in Filter Drawer --}}
        <aside x-cloak x-show="drawerOpen" x-transition.opacity class="fixed inset-0 z-40">
            <div class="absolute inset-0 bg-black/50" @click="drawerOpen=false"></div>

            <div x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="absolute left-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl p-5 overflow-y-auto">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">{{ __('shop.filters') }}</h3>
                    <button @click="drawerOpen=false" class="text-2xl leading-none">&times;</button>
                </div>

                <label class="block text-sm font-medium text-gray-700">{{ __('shop.search') }}</label>
                <input type="text" x-model="q"
                    class="mt-1 mb-4 w-full rounded-lg border-gray-300 focus:border-amber-700 focus:ring-amber-700"
                    :placeholder="`{{ __('shop.search_products') }}`">

                <label class="block text-sm font-medium text-gray-700">{{ __('shop.category') }}</label>
                <select x-model="category"
                    class="mt-1 mb-4 w-full rounded-lg border-gray-300 focus:border-amber-700 focus:ring-amber-700">
                    <option value="">{{ __('shop.all') }}</option>
                    <template x-for="c in categories" :key="c.id">
                        <option :value="String(c.id)" x-text="c.label"></option>
                    </template>
                </select>

                <label class="block text-sm font-medium text-gray-700">{{ __('shop.size') }}</label>
                <div class="mt-2 mb-4 grid grid-cols-5 gap-2">
                    <template x-for="s in sizesAll" :key="s">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" class="rounded border-gray-300 text-amber-700 focus:ring-amber-700"
                                :value="s" x-model="sizesSelected">
                            <span x-text="s"></span>
                        </label>
                    </template>
                    <template x-if="sizesAll.length === 0">
                        <span class="col-span-3 text-xs text-gray-500">{{ __('shop.no_sizes') }}</span>
                    </template>
                </div>

                <label class="block text-sm font-medium text-gray-700">{{ __('shop.sort_by') }}</label>
                <select x-model="sort"
                    class="mt-1 mb-6 w-full rounded-lg border-gray-300 focus:border-amber-700 focus:ring-amber-700">
                    <option value="latest">{{ __('shop.latest') }}</option>
                    <option value="price_low">{{ __('shop.price_low') }}</option>
                    <option value="price_high">{{ __('shop.price_high') }}</option>
                    <option value="discount">{{ __('shop.top_discounts') }}</option>
                </select>

                <div class="flex gap-2">
                    <button @click="reset()"
                        class="flex-1 inline-flex items-center justify-center rounded-lg border px-4 py-2
                                   bg-amber-700 text-white hover:bg-amber-800 transition">
                        {{ __('shop.reset') }}
                    </button>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 py-8">
            <div x-show="justFiltered" x-transition
                class="mb-4 rounded-lg bg-emerald-50 text-emerald-800 px-4 py-2 text-sm">
                {{ __('shop.filters_applied') }}
            </div>

            {{-- Grouped by category --}}
            <template x-for="section in grouped" :key="section.id ?? section.label">
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-3 sm:mb-4 mt-4">
                        <h3 class="text-2xl sm:text-3xl font-bold text-neutral-900" x-text="section.label"></h3>
                        <a :href="viewAllUrl(section.id)" class="text-sm text-neutral-700 hover:text-amber-700">
                            {{ __('shop.view_all') }} →
                        </a>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 border-b border-black/10 pb-8">
                        <template x-for="item in section.items" :key="item.id">
                            <div x-transition x-data="{
                                item,
                                wished: false,
                                busy: false,
                                init() {}"
                                class="product-box relative flex flex-col justify-between px-2 py-5 transition rounded-2xl duration-500
                                        hover:shadow-2xl hover:-translate-y-2 hover:border-black/5 bg-white">

                                <div>
                                    <template x-if="item.images && item.images.length">
                                        <div x-data="{ current: 0 }"
                                            class="relative flex justify-center items-center w-full h-[300px] overflow-hidden rounded-t-md p-2 bg-white">
                                            <template x-for="(img, index) in item.images" :key="index">
                                                <img x-show="current === index" x-transition
                                                    :src="`${storageBase}/${img.path}`"
                                                    @click="showModal = true; modalImage = `${storageBase}/${img.path}`"
                                                    class="h-full object-contain cursor-zoom-in rounded-md mix-blend-multiply"
                                                    :alt="item.name">
                                            </template>

                                            {{-- Gold thumbnail squares --}}
                                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                                                <template x-for="(thumb, tIndex) in item.images" :key="`thumb-${tIndex}`">
                                                    <button type="button" @click="current = tIndex"
                                                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-[6px] overflow-hidden border-2 transition shadow-sm"
                                                        :class="current === tIndex ?
                                                            'border-white ring-2 ring-amber-500/60' :
                                                            'border-white/80 opacity-80 hover:opacity-100'">
                                                        <img :src="`${storageBase}/${thumb.path}`" alt=""
                                                            class="w-full h-full object-cover">
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="p-3 sm:p-4">
                                        <h3 class="text-base sm:text-lg montserrat-bold text-neutral-900 tracking-wide"
                                            x-text="item.name"></h3>
                                        <p class="mt-1 text-sm sm:text-[15px] text-neutral-800/90 font-mono line-clamp-3"
                                            x-html="item.description"></p>

                                        <div class="flex items-center justify-between mt-3">
                                            <template x-if="hasPromo(item)">
                                                <div class="flex items-center gap-2 sm:gap-3">
                                                    <span class="text-lg sm:text-xl font-semibold text-amber-700"
                                                        x-text="`{{ __('shop.currency_aed') }} ${formatPrice(item.discount_price)}`"></span>
                                                    <span class="hidden sm:inline text-gray-500 line-through"
                                                        x-text="`{{ __('shop.currency_aed') }} ${formatPrice(item.price)}`"></span>
                                                    <span
                                                        class="text-[10px] px-1.5 py-0.5 font-bold text-white bg-emerald-600 rounded-md"
                                                        x-text="`-${promoPercent(item)}%`"></span>
                                                </div>
                                            </template>

                                            <template x-if="!hasPromo(item)">
                                                <span class="text-lg sm:text-2xl font-semibold text-amber-800"
                                                    x-text="`{{ __('shop.currency_aed') }} ${formatPrice(item.price)}`"></span>
                                            </template>
                                        </div>

                                    </div>
                                </div>
                                <a :href="productUrl(item.slug)"
                                    class="inline-flex items-center justify-center px-5 py-2 rounded-full
          text-white font-medium
          bg-amber-700 hover:bg-amber-800
          transition-transform duration-200 hover:-translate-y-0.5">
                                    {{ __('shop.view') }}

                                </a>


                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="grouped.length === 0" class="py-20 text-center text-gray-600">
                {{ __('shop.no_products_found') }}
            </div>
        </div>

        {{-- Image Zoom Modal --}}
        <div x-cloak x-show="showModal" x-transition
            class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="showModal = false" role="dialog" :aria-label="`{{ __('shop.image_preview') }}`">
            <div class="relative max-w-full max-h-screen">
                <img @click.outside="showModal = false" :src="modalImage"
                    class="max-w-full max-h-[90vh] rounded shadow-xl" :alt="`{{ __('shop.image_preview') }}`">
            </div>
        </div>
    </div>

    <script>
        function page(products = [], categories = [], storageBase = '', sizeOptions = []) {
            return {
                // state
                drawerOpen: false,
                showModal: false,
                modalImage: '',
                storageBase,
                products,
                categories,
                sizesAll: sizeOptions,

                q: new URLSearchParams(location.search).get('q') || '',
                category: new URLSearchParams(location.search).get('category') || '',
                sort: new URLSearchParams(location.search).get('sort') || 'latest',
                sizesSelected: (() => {
                    const raw = new URLSearchParams(location.search).get('sizes') || '';
                    return raw ? raw.split(',').map(s => s.trim()).filter(Boolean) : [];
                })(),

                justFiltered: false,

                // computed
                get filtered() {
                    let list = [...this.products];

                    const q = this.q.trim().toLowerCase();
                    if (q) {
                        list = list.filter(p => {
                            const n = (p.name || '').toLowerCase();
                            const d = (p.description || '').toLowerCase();
                            return n.includes(q) || d.includes(q);
                        });
                    }

                    if (this.category) {
                        list = list.filter(p => String(p.category_id) === String(this.category));
                    }

                    if (this.sizesSelected.length > 0) {
                        const set = new Set(this.sizesSelected.map(s => s.toLowerCase()));
                        list = list.filter(p => (p.sizes || []).some(sz => set.has(String(sz).toLowerCase())));
                    }

                    switch (this.sort) {
                        case 'price_low':
                            list.sort((a, b) => (a.discount_price ?? a.price) - (b.discount_price ?? b.price));
                            break;
                        case 'price_high':
                            list.sort((a, b) => (b.discount_price ?? b.price) - (a.discount_price ?? a.price));
                            break;
                        case 'discount':
                            const disc = p => (p.price > 0 && p.discount_price != null) ? (p.price - p.discount_price) /
                                p.price : 0;
                            list.sort((a, b) => disc(b) - disc(a));
                            break;
                        case 'latest':
                        default:
                            break;
                    }

                    return list;
                },

                get grouped() {
                    const map = new Map();
                    for (const p of this.filtered) {
                        const label = p.category_label || '{{ __('shop.uncategorized') }}';
                        const id = p.category_id ?? null;
                        if (!map.has(label)) map.set(label, {
                            label,
                            id,
                            items: []
                        });
                        map.get(label).items.push(p);
                    }
                    return Array.from(map.values());
                },

                // actions
                apply() {
                    this.justFiltered = true;
                    setTimeout(() => this.justFiltered = false, 1200);

                    const u = new URL(location);
                    this.q ? u.searchParams.set('q', this.q) : u.searchParams.delete('q');
                    this.category ? u.searchParams.set('category', this.category) : u.searchParams.delete('category');
                    this.sort ? u.searchParams.set('sort', this.sort) : u.searchParams.delete('sort');

                    if (this.sizesSelected.length) {
                        u.searchParams.set('sizes', this.sizesSelected.join(','));
                    } else {
                        u.searchParams.delete('sizes');
                    }

                    history.replaceState({}, '', u);
                },

                reset() {
                    this.q = '';
                    this.category = '';
                    this.sort = 'latest';
                    this.sizesSelected = [];
                    this.apply();
                },

                // helpers
                formatPrice(v) {
                    return Number(v ?? 0).toFixed(2);
                },
                hasPromo(p) {
                    const price = Number(p?.price ?? 0);
                    const disc = Number(p?.discount_price ?? 0);
                    return Number.isFinite(price) && Number.isFinite(disc) && price > 0 && disc > 0 && disc < price;
                },
                promoPercent(p) {
                    if (!this.hasPromo(p)) return 0;
                    const price = Number(p.price);
                    const disc = Number(p.discount_price);
                    return Math.round(((price - disc) / price) * 100);
                },
                productUrl(idOrSlug) {
                    return `{{ route('products.show', 0) }}`.replace('/0', `/${idOrSlug}`);
                },
                viewAllUrl(categoryId) {
                    const u = new URL(`{{ route('products.index') }}`);
                    if (categoryId) u.searchParams.set('category', String(categoryId));
                    return u.toString();
                },

            }
        }
    </script>
@endsection
