<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingOptionCity;
use App\Models\ShippingOptions;
use Arr;
use DB;
use Illuminate\Http\Request;

class ShippingOptionController extends Controller
{
    /**
     * Display all shipping options.
     */
    public function index()
    {
        $shippingOptions = ShippingOptions::where('status', 1)->with('cityItems')->latest()->paginate(10);
        return view('admin.shipping-options', compact('shippingOptions'));
    }

    /**
     * Store a new shipping option.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'required|string|max:100',
            'cities' => 'required|array|min:1',
            'cities.*' => 'required|string|max:190|distinct',
        ]);

        // Normalize cities (trim, remove empties, de-duplicate)
        $cities = collect($validated['cities'])
            ->map(fn($c) => trim((string) $c))
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($validated, $cities) {
            /** @var \App\Models\ShippingOptions $option */
            $option = ShippingOptions::create(Arr::except($validated, ['cities']));

            $option->cityItems()->createMany(
                collect($cities)->map(fn($c) => ['city' => $c])->all()
            );
        });

        return redirect()->back()->with('success', 'Shipping option added successfully.');
    }

    public function update(Request $request, ShippingOptions $shippingOption)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'required|string|max:100',
            'cities' => 'required|array|min:1',
            'cities.*' => 'required|string|max:190|distinct',
        ]);

        // Normalize incoming cities (trim + unique)
        $cities = collect($validated['cities'])
            ->map(fn($c) => trim((string) $c))
            ->filter()
            ->unique()
            ->values();

        DB::transaction(function () use ($shippingOption, $validated, $cities) {
            // Update the parent record
            $shippingOption->update(Arr::except($validated, ['cities']));

            // Prepare rows to insert/update (no deletions)
            $rows = $cities->map(fn($c) => [
                'shipping_option_id' => $shippingOption->id,
                'city' => $c,
                'updated_at' => now(),
                'created_at' => now(),
            ])->all();

            // Upsert by composite unique (shipping_option_id, city)
            // - Inserts new cities
            // - Leaves existing rows intact (just touches updated_at)
            ShippingOptionCity::upsert(
                $rows,
                ['shipping_option_id', 'city'],
                ['updated_at'] // columns to update on conflict
            );
        });

        return back()->with('success', 'Shipping option updated successfully.');
    }

    /**
     * Delete a shipping option.
     */
    public function destroy(ShippingOptions $shippingOption)
    {
        $shippingOption->status = 0;

        $shippingOption->save();
        return redirect()->back()->with('success', 'Shipping option deleted.');
    }
}