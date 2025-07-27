<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StoreController extends Controller
{
   public function home()
{

    $products = Product::with(['images', 'category'])
    ->latest()
    ->get()
    ->groupBy(fn($p) => $p->category->name ?? 'Uncategorized');

    return view('home', compact('products'));
}

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}
