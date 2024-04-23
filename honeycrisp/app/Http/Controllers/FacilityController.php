<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::all();
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $facility = new Facility();
        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->abbreviation = $request->abbreviation;
        $facility->email= $request->email;
        $facility->recharge_account = $request->recharge_account;
        $facility->address = $request->address;

        $facility->save();

        return redirect()->route('facilities.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->abbreviation = $request->abbreviation;
        $facility->email= $request->email;
        $facility->recharge_account = $request->recharge_account;
        $facility->address = $request->address;

        $facility->save();

        if($facility->wasChanged()){
            $state = 'success';
            $msg = $facility->name . ' has been updated';
        } else {
            $state = 'alert';
            $msg = $facility->name . ' was not updated';
        }

        return redirect()->route('facilities.index')->with($state, $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        $msg = $facility->name . ' has been deleted';
        $facility->delete();

        return redirect()->route('facilities.index')->with('alert', $msg);
    }
}
