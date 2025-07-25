<!DOCTYPE html>
<html>

<head>
    <title>Order Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Receipt for Order {{ $order->order_number }}</h1>
    <p><strong>Customer:</strong> {{ $order->full_name }} ({{ $order->email }})</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted Item' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
</body>

</html>
