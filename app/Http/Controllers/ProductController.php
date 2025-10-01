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

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['images'])->first();
        if (!$product) {
            $product = Product::with(['images'])->find($slug);
            if (!$product) {
                abort(404);
            }
        }
        $smiliarProducts = Product::where('category_id', $product->category_id)->whereKeyNot($product->getKey())->with('images')->limit(4)->get();
        return view('products.show', compact('product', 'smiliarProducts'));
    }
}
