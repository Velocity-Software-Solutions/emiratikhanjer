@extends('layouts.app')

@section('content')
    <div class="max-w-5xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div>

                @if ($products->images->count())
                    <img src="{{ asset('storage/' . $products->images->first()->image_path) }}" alt="{{ $product->name }}"
                        class="w-full rounded-lg shadow-lg">
                @endif
            </div>

            <div>
                <h1 class="mb-4 text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="mb-6 text-lg text-gray-600">{{ $product->description }}</p>

                <div class="mb-6 text-2xl font-semibold text-blue-600">
                    ${{ number_format($product->price, 2) }}
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center space-x-4">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
