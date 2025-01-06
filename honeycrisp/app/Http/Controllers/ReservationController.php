<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Facility;

class ReservationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    public function create($facilityAbbreviation)
    {

        $facility = Facility::where('abbreviation', $facilityAbbreviation)->first();
        $products = Product::where('facility_id', $facility->id)->where('can_reserve', true)->get();
        return view('reservations.create', compact('products'));
    }

    public function createForProduct(Product $product)
    {
        // Ensure the product is reservable
        if (!$product->can_reserve) {
            return redirect()->route('reservations.create', $product->facility->abbreviation)
                ->with('error', 'This product cannot be reserved.');
        }

        // Fetch schedule rules for the product
        $scheduleRules = $product->scheduleRules;

        return view('reservations.createForProduct', compact('product', 'scheduleRules'));
    }





    public function store(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product->can_reserve) {
            return back()->withErrors(['error' => 'This product cannot be reserved.']);
        }

        $start = new \DateTime($request->reservation_start);
        $end = new \DateTime($request->reservation_end);

        if (!$product->isReservable($start, $end)) {
            return back()->withErrors(['error' => 'The reservation does not match the product\'s schedule rules.']);
        }

        Reservation::create([
            'product_id' => $product->id,
            'order_id' => $request->order_id,
            'reservation_start' => $request->reservation_start,
            'reservation_end' => $request->reservation_end,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }
}
