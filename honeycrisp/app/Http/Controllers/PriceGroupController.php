<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PriceGroup;

class PriceGroupController extends Controller
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
    public function create(Product $product)
    {
        

        return view('price-groups.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newPriceGroup = new PriceGroup();
        $newPriceGroup->product_id = $request->product_id;
        $newPriceGroup->name = $request->name;
        $newPriceGroup->price = $request->price * 100;
        $newPriceGroup->start_date = $request->start_date;
        $newPriceGroup->end_date = $request->end_date;
        $newPriceGroup->save();

        return redirect()->route('products.show', $newPriceGroup->product_id)->with('success', 'Price Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $priceGroup = PriceGroup::find($id);

        return view('price-groups.edit', compact('priceGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $priceGroup = PriceGroup::find($id);
        $priceGroup->name = $request->name;
        
        // price is in dollars, so convert to cents
        $priceGroup->price = $request->price * 100;

        $priceGroup->start_date = $request->start_date;
        $priceGroup->end_date = $request->end_date;
        $priceGroup->save();

        return redirect()->route('products.show', $priceGroup->product_id)->with('success', 'Price Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $priceGroup = PriceGroup::find($id);
        $product_id = $priceGroup->product_id;
        $priceGroup->delete();

        return redirect()->route('products.show', $product_id)->with('success', 'Price Group deleted successfully.');
    }
}
