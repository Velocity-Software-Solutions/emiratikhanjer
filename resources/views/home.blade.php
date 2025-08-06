@extends('layouts.app')

@section('content')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div x-data="{ showModal: false, modalImage: '' }" class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 fade-up">
            <h2 class="mb-8 montaga-regular text-4xl sm:text-5xl font-bold text-yellow-800">Featured Products</h2>

            @foreach ($products as $category => $groupedProducts)
                <h3 class="mt-10 mb-6 text-3xl font-semibold text-gray-700 border-b pb-2">{{ $category }}</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($groupedProducts as $product)
                        <div class="overflow-hidden !transition bg-white rounded-lg shadow-md !duration-500 hover:shadow-2xl fade-up">
                            @if ($product->images->count())
                                <div x-data="{ current: 0 }"
                                    class="relative flex justify-center items-center w-full h-[515px] overflow-hidden rounded-t-md p-2">
                                    <template x-for="(img, index) in {{ $product->images->take(3)->toJson() }}"
                                        :key="index">
                                        <img x-show="current === index" x-transition :src="'/storage/' + img.image_path"
                                            @click="showModal = true; modalImage = '/storage/' + img.image_path"
                                            {{-- @click="$dispatch('open-modal', { src: '/storage/' + img.image_path })" --}} class="h-full object-contain cursor-zoom-in rounded-md"
                                            alt="{{ $product->name }}">
                                    </template>

                                    <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2">
                                        <template x-for="(img, index) in {{ $product->images->take(3)->toJson() }}"
                                            :key="index">
                                            <button @click="current = index"
                                                :class="{ 'bg-yellow-600': current === index, 'bg-gray-300': current !== index }"
                                                class="w-3 h-3 rounded-full"></button>
                                        </template>
                                    </div>
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="mt-1 text-gray-600">{{ Str::limit($product->description, 80) }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xl font-bold text-blue-600">
                                        AED {{ number_format($product->price, 2) }}
                                    </span>
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="inline-block px-3 py-1 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Zoom Modal --}}
        <div x-show="showModal" x-transition x-cloak
            class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4"
            @click.away="showModal = false" @keydown.escape.window="showModal = false">
            <div class="relative max-w-full max-h-screen">
                <button @click="showModal = false"
                    class="absolute -top-4 -right-4 flex justify-center items-center bg-white text-black text-2xl w-8 h-8 rounded-full shadow hover:bg-gray-100 z-50"
                    aria-label="Close">
                    <span class="material-icons">
                        close
                    </span>
                </button>
                <img :src="modalImage" class="max-w-full max-h-[90vh] rounded shadow-xl">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', (images) => ({
                current: 0,
                images,
                next() {
                    this.current = (this.current + 1) % this.images.length
                },
                prev() {
                    this.current = (this.current - 1 + this.images.length) % this.images.length
                }
            }))
        });
    </script>
@endsection
