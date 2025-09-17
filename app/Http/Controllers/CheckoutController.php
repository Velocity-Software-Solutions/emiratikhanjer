<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingOptions;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // at the top of the file


class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $coupon = session('coupon');

        $countries = [
            'UAE' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'],
            'Saudi Arabia' => ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Dhahran', 'Tabuk', 'Abha', 'Hail'],
            'Kuwait' => ['Kuwait City', 'Salmiya', 'Hawally', 'Farwaniya', 'Jahra', 'Fahaheel', 'Mangaf', 'Sabah Al Salem', 'Mahboula', 'Abu Halifa'],
            'Qatar' => ['Doha', 'Al Rayyan', 'Umm Salal', 'Al Wakrah', 'Al Khor', 'Al Daayen', 'Al Shamal', 'Al Shahaniya'],
            'Oman' => ['Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur', 'Ibri', 'Barka', 'Rustaq'],
            'Bahrain' => ['Manama', 'Muharraq', 'Riffa', 'Isa Town', 'Sitra', 'Budaiya', 'Hamad Town', 'A\'ali'],
        ];

        $shippingOptions = ShippingOptions::where('status', 1)->with('cityItems')->get();

        $subtotal = array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
        $discount = $coupon
            ? ($coupon->discount_type === 'percentage'
                ? ($coupon->value / 100) * $subtotal
                : $coupon->value)
            : 0;

        $total = max(0, $subtotal - $discount);

        return view('checkout.index', compact('cart', 'total', 'coupon', 'discount', 'subtotal', 'countries', 'shippingOptions'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'email' => auth()->check() ? 'nullable|email' : 'required|email',
            'full_name' => 'required|string|max:255',
            'country' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string|max:25|regex:/^\+?[0-9\s\-]{7,20}$/',
            'shipping_option_id' => 'required|exists:shipping_options,id',
            'shipping_cost' => 'required|numeric', // kept for form compatibility; price is taken from DB
            'shipping_address' => 'required|string',
            'billing_address' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        $coupon = session('coupon');

        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }

        $email = auth()->check() ? auth()->user()->email : $request->email;
        if (!auth()->check()) {
            session(['guest_email' => $email]); // optional UX
        }

        // Compute subtotal/discount (you can reprice from DB if you prefer)
        $subtotal = array_reduce($cart, fn($sum, $i) => $sum + ((float) $i['price'] * (int) $i['quantity']), 0.0);
        $discount = $coupon
            ? ($coupon->discount_type === 'percentage'
                ? ($coupon->value / 100) * $subtotal
                : (float) $coupon->value)
            : 0.0;

        try {
            DB::beginTransaction();

            // Validate shipping option and pull authoritative shipping price from DB
            /** @var \App\Models\ShippingOptions|null $shipping */
            $shipping = ShippingOptions::with('cityItems')
                ->where('status', 1)
                ->find($request->shipping_option_id);

            if (
                !$shipping ||
                $shipping->country !== $request->country ||
                !$shipping->cityItems->pluck('city')->contains($request->city)
            ) {
                DB::rollBack();
                return back()
                    ->withErrors(['shipping_option_id' => 'Selected shipping method is not available for this country/city.'])
                    ->withInput();
            }

            $shippingCost = (float) $shipping->price; // trust DB

            // Lock all products to avoid oversell
            $productIds = array_map('intval', array_keys($cart));
            /** @var \Illuminate\Support\Collection<int,\App\Models\Product> $products */
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $insufficient = [];

            foreach ($cart as $productId => $item) {
                $productId = (int) $productId;
                $reqQty = (int) ($item['quantity'] ?? 0);
                /** @var Product|null $product */
                $product = $products->get($productId);

                $name = $item['name'] ?? optional($product)->name ?? "Product #{$productId}";

                if (!$product) {
                    $insufficient[] = "{$name}: product not found.";
                    continue;
                }
                if ($reqQty < 1) {
                    $insufficient[] = "{$name}: invalid quantity.";
                    continue;
                }

                // Use stock_quantity (authoritative)
                $available = (int) ($product->stock_quantity ?? 0);
                if ($available < $reqQty) {
                    $insufficient[] = "{$name}: only {$available} left in stock.";
                }
            }

            if (!empty($insufficient)) {
                DB::rollBack();
                return back()
                    ->withErrors(['cart' => "Some items can’t be fulfilled:\n• " . implode("\n• ", $insufficient)])
                    ->withInput();
            }

            // Create order
            $total = max(0, $subtotal - $discount + $shippingCost);

            $order = Order::create([
                'user_id' => auth()->id(),
                'email' => $email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone,
                'subtotal_amount' => $subtotal,     // if columns exist
                'discount_amount' => $discount,     // "
                'shipping_cost' => $shippingCost, // "
                'total_amount' => $total,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'payment_method' => 'manual',
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'coupon_id' => $coupon->id ?? null,
                'shipping_option_id' => $shipping->id,
                'country' => $request->country,
                'city' => $request->city,
            ]);

            // Create items and decrement stock_quantity
            foreach ($cart as $productId => $item) {
                $productId = (int) $productId;
                $reqQty = (int) $item['quantity'];
                $price = (float) $item['price']; // optionally reprice from DB

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $reqQty,
                    'price' => $price,
                    'subtotal' => $reqQty * $price,
                ]);

                /** @var Product $product */
                $product = $products->get($productId);
                $product->decrement('stock_quantity', $reqQty);
            }

            DB::commit();

            Mail::to($email)->send(new \App\Mail\OrderConfirmationMail($order));

            session()->forget(['cart', 'coupon']);

            return redirect()->route('checkout.confirmation', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getFile() . ':' . $e->getLine());
        }

    }

    public function confirmation(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('checkout.confirmation', compact('order'));
    }

    public function downloadReceipt(Order $order)
    {
        // Optional: restrict access to owner
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to receipt.');
        }

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('checkout.receipt', compact('order'));

        return $pdf->download("receipt-{$order->order_number}.pdf");
    }
}
