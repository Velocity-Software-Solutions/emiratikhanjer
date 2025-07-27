@extends('layouts.app')

@section('content')
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="mb-6 text-3xl font-bold text-gray-900">Your Shopping Cart</h1>
        <form action="{{ route('cart.applyCoupon') }}" class="py-4" method="POST">
            @csrf
            <label for="coupon">Have a coupon?</label>
            <input type="text" name="coupon_code" placeholder="Enter coupon code" class="p-2 border rounded">
            <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded">Apply</button>
        </form>

        @if (session('coupon'))
            <p class="mt-2 text-green-600">Coupon applied: <strong>{{ session('coupon')->code }}</strong></p>
        @endif

        @if (session('cart') && count(session('cart')) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow">
                    <thead>
                        <tr class="text-sm font-semibold text-left text-gray-700 bg-gray-100">
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Quantity</th>
                            <th class="px-6 py-4">Subtotal</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach (session('cart') as $id => $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr class="border-t">
                                <td class="flex items-center px-6 py-4 space-x-4">
                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                        class="object-cover w-12 h-12 rounded" alt="">
                                    <span>{{ $item['name'] }}</span>
                                </td>
                                <td class="px-6 py-4">AED {{ number_format($item['price'], 2) }}</td>
                                <td class="px-6 py-4">{{ $item['quantity'] }}</td>
                                <td class="px-6 py-4">AED {{ number_format($subtotal, 2) }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('cart.remove', $id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-right">
                <h3 class="text-xl font-semibold text-gray-800">Total: AED {{ number_format($total, 2) }}</h3>
                <a href="{{ route('checkout.index') }}"
                    class="inline-block px-6 py-2 mt-4 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                    Proceed to Checkout
                </a>
            </div>
        @else
            <div class="text-gray-600">
                Your cart is empty.
            </div>
        @endif
    </div>
@endsection
