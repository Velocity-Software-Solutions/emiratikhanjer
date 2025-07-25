@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')


<div class="max-w-4xl w-3/4 mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
  <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">✏️ Edit Product</h2>
@if($product->images && $product->images->count())
  <div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">📷 Current Images</h3>
    <div class="flex flex-wrap gap-4">
      @foreach($product->images as $image)
        <div class="w-32 h-32 overflow-hidden rounded border border-gray-300 dark:border-gray-600 shadow-sm">
          <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?? 'Product Image' }}" class="w-full h-full object-cover">
        </div>
      @endforeach
    </div>
  </div>
@endif

  <form action="{{ route('admin.admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Product Name -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

      <!-- SLUG -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SLUG</label>
      <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- SKU -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
      <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
      <textarea name="description" rows="4" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('description', $product->description) }}</textarea>
      @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Category -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
      <select name="category_id" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">-- Select Category --</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
      @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Price & Discount -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Price</label>
        <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" step="0.01" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        @error('discount_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
    </div>

    <!-- stock_quantity -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity in Stock</label>
      <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      @error('stock_quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Condition -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Condition</label>
      <select name="condition" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">-- Select Condition --</option>
        @foreach (['new', 'used', 'antique'] as $condition)
          <option value="{{ $condition }}" @selected(old('condition', $product->condition) == $condition)>
            {{ ucfirst($condition) }}
          </option>
        @endforeach
      </select>
      @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Tags -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
      <input type="text" name="tags" value="{{ old('tags', $product->tags) }}" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      @error('tags') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Upload New Images -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Images</label>
      <input type="file" name="images[]" multiple class="w-full mt-1 text-gray-800 dark:text-white">
      @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Status Toggle -->
    <div class="flex items-center">
      <label class="mr-4 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
      <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', $product->status) ? 'checked' : '' }}>
        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer dark:bg-gray-600 peer-checked:bg-green-500"></div>
      </label>
    </div>

    <!-- Submit -->
    <div>
      <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update Product</button>
    </div>
  </form>
</div>
@endsection
