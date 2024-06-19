<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Facility;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($facilityAbbreviation)
    {
        $facility = Facility::all()->where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first();

        return view('products.create', compact('facility'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->unit = $request->unit;
        $product->unit_price = $request->unit_price;
        $product->requires_approval = $request->requires_approval;
        $product->is_active = $request->is_active;
        $product->is_deleted = $request->is_deleted;
        $product->image_url = $request->image_url;
        $product->tags = $request->tags;
        $product->facility_id = $request->facility_id;
        $product->category_id = $request->category_id ?? null;

        $product->save();

        return redirect()->route('facilities.edit', $product->facility_id)->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->name;
        $product->description = $request->description;
        $product->unit = $request->unit;
        $product->unit_price = $request->unit_price;
        $product->requires_approval = $request->requires_approval;
        $product->is_active = $request->is_active;
        $product->is_deleted = $request->is_deleted;
        $product->image_url = $request->image_url;
        $product->tags = $request->tags;
        $product->facility_id = $request->facility_id;
        $product->category_id = $request->category_id ?? null;

        $product->save();

        return redirect()->route('facilities.edit', $product->facility_id)->with('success', 'Product updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
