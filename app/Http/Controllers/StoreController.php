<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StoreController extends Controller
{
   public function home()
{
    $products = Product::with('images')->latest()->take(6)->get();

    return view('home', compact('products'));
}

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}
