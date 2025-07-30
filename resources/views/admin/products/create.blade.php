@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="mx-2 bg-white dark:bg-gray-800 p-6 shadow-md rounded-tl-md rounded-tr-md overflow-scroll custom-scrollbar scrollbar-hide">
  <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">➕ Add New Product</h2>

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Product Name -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
      <input type="text" name="name" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('name') }}">
      @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

      <!-- SLUG -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SLUG</label>
      <input type="text" name="slug" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('slug') }}">
      @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>


    <!-- SKU -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
      <input type="text" name="sku" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('sku') }}">
      @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
      <textarea name="description" rows="4" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
      @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Category -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
      <select name="category_id" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white">
        <option value="">-- Select Category --</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
      @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Price and Discount -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
        <input type="number" name="price" step="0.01" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('price') }}">
        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Price</label>
        <input type="number" name="discount_price" step="0.01" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('discount_price') }}">
        @error('discount_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
    </div>

    <!-- Quantity -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity in Stock</label>
      <input type="number" name="stock_quantity" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('stock_quantity') }}">
      @error('stock_quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Condition -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Condition</label>
      <select name="condition" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white">
        <option value="">-- Select Condition --</option>
        <option value="new" @selected(old('condition') == 'new')>New</option>
        <option value="used" @selected(old('condition') == 'used')>Used</option>
        <option value="antique" @selected(old('condition') == 'antique')>Antique</option>
      </select>
      @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Tags -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
      <input type="text" name="tags" placeholder="e.g., vintage, silver, handmade" class="w-full mt-1 p-2 border rounded-md bg-white dark:bg-gray-700 dark:text-white" value="{{ old('tags') }}">
      @error('tags') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Images -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Images</label>
      <input type="file" name="images[]" multiple class="w-full mt-1 text-gray-800 dark:text-white">
      @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Status -->
    <div class="flex items-center">
      <label class="mr-4 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
      <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status') ? 'checked' : '' }}>
        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer dark:bg-gray-600 peer-checked:bg-green-500"></div>
      </label>
    </div>

    <!-- Submit -->
    <div>
      <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Save Product</button>
    </div>
  </form>
</div>
@endsection
