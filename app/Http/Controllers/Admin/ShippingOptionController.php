<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingOptions;
use Illuminate\Http\Request;

class ShippingOptionController extends Controller
{
    /**
     * Display all shipping options.
     */
    public function index()
    {
        $shippingOptions = ShippingOptions::latest()->paginate(10);
        return view('admin.shipping-options', compact('shippingOptions'));
    }

    /**
     * Store a new shipping option.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'required|string|max:100',
            'cities' => 'nullable|array|min:1',
        'cities.*' => 'nullable|string|max:2000',
        ]);

        ShippingOptions::create($validated);
        return redirect()->back()->with('success', 'Shipping option added successfully.');
    }

    /**
     * Update an existing shipping option.
     */
    public function update(Request $request, ShippingOptions $shippingOption)
    {
        dd($request->all());die();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'required|string|max:100',
            'cities' => 'required|array|min:1',
        'cities.*' => 'required|string|max:2000',
        ]);


        $shippingOption->update($validated);
        return redirect()->back()->with('success', 'Shipping option updated successfully.');
    }

    /**
     * Delete a shipping option.
     */
    public function destroy(ShippingOptions $shippingOption)
    {
        $shippingOption->delete();
        return redirect()->back()->with('success', 'Shipping option deleted.');
    }
}