<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::denies('admin')) {
            return redirect()->route('facilities.index');
        }
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
        $facility->account_type = $request->account_type;

        $facility->save();

        return redirect()->route('facilities.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        // admin gate
        if(Gate::denies('admin')){
            return redirect()->route('facilities.index');
        }
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
        $facility->account_type = $request->account_type;

        $facility->save();

        if($facility->wasChanged()){
            $state = 'success';
            $msg = $facility->name . ' has been updated';
        } else {
            $state = 'alert';
            $msg = $facility->name . ' was not updated (no changes)';
        }

        return redirect()->route('facilities.edit', $facility)->with($state, $msg);
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

    public function exportInvoices($facility)
    {

        $facility = Facility::where('abbreviation', $facility)->first();

        $lines = "";
        $lines = $facility->generateFinancialHeader();
        $glCount = 0;
        $total = 0;

        //for each order, generate the financialLines
        foreach ($facility->orders as $order) {
            $financial_lines = $order->generateFinancialLines();
            
            foreach ($financial_lines as $line) {
                $lines .= $line . "\n";
            }

            $glCount += $order->items->count();
            $total += $order->total;
        }

        $lines .= $facility->generateFinancialFooter($glCount, $total);

        $filename = $facility->abbreviation . '_financials.dat';

        return response($lines)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');


        
    }
}
