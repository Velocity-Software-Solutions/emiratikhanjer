@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $image->alt_text ?? 'Product Image' }}" class="w-full rounded-lg shadow-lg">
                    @endforeach
                </div>


                <div>
                    <h1 class="mb-2 text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                    <p class="mb-4 text-lg text-gray-600">${{ number_format($product->price, 2) }}</p>
                    <p class="mb-6 text-gray-700">{{ $product->description }}</p>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="block w-24 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
