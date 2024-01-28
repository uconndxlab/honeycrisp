<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // facilities get em all
        $facilities = Facility::all();

        // return the view with the data
        return view('facilities.index', [
            'facilities' => $facilities
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request needs a name and description
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        // create a new facility
        $facility = new Facility();
        $facility->name = $request->name;
        $facility->description = $request->description;

        // if the facility is saved, redirect to the index
        // with a success message
        if ($facility->save()) {
            return redirect('/facilities')->with('success', 'Facility created!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get the facility by id and show the single view
        $facility = Facility::find($id);
        if ($facility) {
            return view('facilities.show', [
                'facility' => $facility
            ]);
        } else {
            return redirect('/facilities')->with('error', 'Facility not found!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
