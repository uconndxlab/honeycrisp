<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Facility;
use App\Models\Order;
use App\Models\OrderItem;

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
        return view('reservations.create', compact('products', 'facility'));
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
    
        // get product reservations in the next 30 days
        $reservations = $product->reservations()->where('reservation_start', '>=', now())
            ->where('reservation_start', '<=', now()->addDays(30))
            ->get();

        return view('reservations.createForProduct', compact('product', 'scheduleRules', 'reservations'));
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $reservation->update($request->all());
        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
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

        // create an order to go with it, with a status of 'pending' and a quantity of 1 of the product
        $order = Order::create([
            'status' => 'pending',
            'quantity' => 1,
            'user_id' => auth()->user()->id ?? null,
            'facility_id' => $product->facility_id,
            'title' => 'Reservation for ' . $product->name. ' on ' . $start->format('Y-m-d'),
            'date' => $start->format('Y-m-d'),
            'price_group' => 'internal',
            'description' => 'Reservation for ' . $product->name. ' on ' . $start->format('Y-m-d'),
        ]);


        // add an OrderItem to the order for the product
        $order->items()->create([
            'name' => $product->name,
            'product_id' => $product->id,
            'price' => $product->price,
            'description' => $product->name,
            'price' => 1000, // $10.00
            'quantity' => 1,
        ]);

        // get the order ID
        $order_id = $order->id;

        

        Reservation::create([
            'product_id' => $product->id,
            'reservation_start' => $request->reservation_start,
            'order_id' => $order_id,
            'reservation_end' => $request->reservation_end,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);


        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }


}
