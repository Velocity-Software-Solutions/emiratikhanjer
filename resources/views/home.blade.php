@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-8 text-3xl font-extrabold text-gray-900">Featured Products</h2>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($products as $product)
                    <div class="overflow-hidden transition bg-white rounded-lg shadow-md hover:shadow-lg">
                        @if ($product->images->count())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                alt="{{ $product->name }}" class="object-cover w-full h-56">
                        @endif

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                            <p class="mt-1 text-gray-600">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xl font-bold text-blue-600">
                                    ${{ number_format($product->price, 2) }}
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

        </div>
    </div>
@endsection
