<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {

       $products = Product::with('images')->latest()->take(6)->get();
        return view('products.index', compact(var_name: 'products'));
    }

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('products.show', compact(var_name: 'product'));
    }
}
