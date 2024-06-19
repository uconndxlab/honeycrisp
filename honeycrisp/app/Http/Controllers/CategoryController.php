<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Facility;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($facilityAbbreviation = null)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($facilityAbbreviation = null)
    {
        $facilities = Facility::all();

        if ($facilityAbbreviation) {
            $facility = Facility::where('abbreviation', $facilityAbbreviation)->first();
            $facilities = Facility::where('id', $facility->id)->get();
        }

        return view('categories.create', ['facilities' => $facilities]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->facility_id = $request->facility_id;

        $category->save();

        // redirect to facility show page with success message
        return redirect()->route('facilities.edit', $category->facility_id)->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->description = $request->description;
        $category->facility_id = $request->facility_id;

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
