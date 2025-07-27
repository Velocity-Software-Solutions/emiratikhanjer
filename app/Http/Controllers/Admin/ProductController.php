<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
public function index()
{
    $products = Product::with('category')->latest()->paginate(10); // or ->get()

    return view('admin.products.index', compact('products'));
}



    public function create()
    {
        $categories = Category::all();
         return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate & store product
         $validated = $request->validate([
        'name' => 'required|string|max:255',
         'slug' => 'required|string|unique:products,slug',
        'sku' => 'required|string|unique:products,sku',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'stock_quantity' => 'required|integer|min:0',
        'condition' => 'required|in:new,used,antique',
        'status' => 'boolean',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'nullable|string',
        'images.*' => 'nullable|image|max:2048'
    ]);

    // Create Product
    $product = Product::create([
        ...$validated,
        'slug' => Str::slug($validated['name']),
        'status' => $request->has('status') ? 1 : 0
    ]);

    // Save images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'alt_text' => $product->name
            ]);
        }
    }

    return redirect()->route('admin.admin.products.index')->with('success', 'Product created successfully!');
}
    

 public function edit(Product $product)
{
    $categories = Category::all();
    // Ensure the product has images loaded
    $product->load('images');
    return view('admin.products.edit', compact('product', 'categories'));
}


   public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products,slug,' . $product->id,
        'sku' => 'required|string|unique:products,sku,' . $product->id,
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'stock_quantity' => 'required|integer|min:0',
        'condition' => 'required|in:new,used,antique',
        'status' => 'boolean',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'nullable|string',
        'images.*' => 'nullable|image|max:2048'
    ]);

    $product->update([
        ...$validated,
        'slug' => Str::slug($validated['name']),
        'status' => $request->has('status') ? 1 : 0
    ]);

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'alt_text' => $product->name
            ]);
        }
    }

    return redirect()->route('admin.admin.products.index')->with('success', 'Product updated successfully!');
}


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.admin.products.index')->with('success', 'Product deleted.');
    }
}
