<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Models\Facility;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ledgers = Ledger::all();
        return view('ledgers.index', compact('ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        
        // facilities that have orders with a status of complete
        $facilities = Facility::whereHas('orders', function ($query) {
            $query->where('status', 'complete');
        })->get();

        return view('ledgers.create', compact('facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ledger = Ledger::create($request->all());
        
        return redirect()->route('ledgers.edit', $ledger);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ledger $ledger)
    {
        dd($ledger);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ledger $ledger)
    {
       
        
        return view('ledgers.edit', compact('ledger'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ledger $ledger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ledger $ledger)
    {
        $ledger->delete();
        // redirect with a message "ledger deleted"

        return redirect()->route('ledgers.index')->with('success', 'Ledger deleted successfully!');
        
    }
}
