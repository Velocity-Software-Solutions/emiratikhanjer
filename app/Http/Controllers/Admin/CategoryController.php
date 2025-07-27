<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        $categories_count = Category::count();

        return view('admin.categories', compact('categories', 'categories_count'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('category_id', null); // No ID since it's a new row
        }

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, string $id)
    {
        $fieldName = 'name_' . $id;
        $fieldSlug = 'slug_' . $id;

        $validator = Validator::make($request->all(), [
            $fieldName => 'required|string|max:255',
            $fieldSlug => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('category_id', $id);
        }
        
        $category = Category::findOrFail($id);
        $category->name = $request->input($fieldName);
        $category->slug = $request->input($fieldSlug);
        $category->save();
        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted.');
    }
}