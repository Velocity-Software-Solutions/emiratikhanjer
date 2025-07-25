@extends('layouts.app')

@section('content')
    <div class="max-w-3xl px-6 py-10 mx-auto bg-white rounded shadow">
        <h1 class="mb-6 text-3xl font-bold text-green-700">Order Confirmed!</h1>

        <p class="mb-4 text-lg">Thank you for your purchase. Your order number is:</p>
        <p class="mb-4 font-mono text-xl font-semibold text-gray-700">{{ $order->order_number }}</p>

        <p class="mb-6">We've sent a confirmation email to <strong>{{ $order->user->email ?? 'your email' }}</strong>.</p>

        <div class="p-4 mb-6 bg-gray-100 border rounded">
            <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Billing:</strong> {{ $order->billing_address }}</p>
            <p><strong>Shipping Method:</strong> {{ $order->shippingOption->name ?? 'N/A' }}</p>
            <p><strong>Total Paid:</strong> ${{ number_format($order->total_amount, 2) }}</p>
        </div>

        <form action="{{ route('checkout.receipt', $order->id) }}" method="GET" target="_blank">
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">
                Download Receipt
            </button>
        </form>


        <a href="{{ route('home') }}" class="ml-4 text-blue-600 underline">Back to Shop</a>
    </div>
@endsection
