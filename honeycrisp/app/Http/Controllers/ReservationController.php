<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Facility;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PriceGroup;

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

    public function createForProduct(Request $request, Product $product)
    {

        // Ensure the product is reservable
        if (!$product->can_reserve) {
            return redirect()->route('reservations.create', $product->facility->abbreviation)
                ->with('error', 'This product cannot be reserved.');
        }

        $reservation_date = $request->reservation_date ?? now()->format('Y-m-d');
        $reservation_start = $request->reservation_start ?? null;
        $reservation_end = $request->reservation_end ?? null;
        $user_id = request()->user_id ?? auth()->user()->id;
        $selected_user = User::where('id', $user_id)->first();
        $accounts = $selected_user->paymentAccounts;
        $reservations_for_date = Reservation::where('product_id', $product->id)
            ->whereDate('reservation_start', $reservation_date)
            ->get();

        $scheduleRules = $product->scheduleRulesForDay($reservation_date);
        $availableStartTimes = [];

        foreach ($scheduleRules as $rule) {
            $ruleStart = new \DateTime($rule->time_of_day_start);
            $ruleEnd = new \DateTime($rule->time_of_day_end);

            $ruleStart = \DateTime::createFromFormat('Y-m-d H:i', $reservation_date . ' ' . $ruleStart->format('H:i'));
            $ruleEnd = \DateTime::createFromFormat('Y-m-d H:i', $reservation_date . ' ' . $ruleEnd->format('H:i'));

            $interval = $product->reservation_interval;
            $current = clone $ruleStart;
            while ($current < $ruleEnd) {
            $next = clone $current;
            $next->add(new \DateInterval('PT' . $interval . 'M'));

            if (!$product->isBooked($current, $next)) {
                $availableStartTimes[] = $current->format('H:i');
            }

            $current->add(new \DateInterval('PT' . $interval . 'M'));
            }
        }

        $reservations = $reservations_for_date;

        $availableEndTimes = [];

        // if start time is selected, calculate available end times
        if ($reservation_start) {
            $start = \DateTime::createFromFormat('Y-m-d H:i', $reservation_date . ' ' . $reservation_start);
            $end = \DateTime::createFromFormat('Y-m-d H:i', $reservation_date . ' ' . $reservation_start);

            $interval = $product->reservation_interval;
            $current = clone $start;
            while ($current < $ruleEnd) {
            $next = clone $current;
            $next->add(new \DateInterval('PT' . $interval . 'M'));

            if (!$product->isBooked($current, $next)) {
                $availableEndTimes[] = $next->format('H:i');
            } else {
                break; // Stop if the next slot is booked
            }

            $current->add(new \DateInterval('PT' . $interval . 'M'));
            }
        }



        $facility = $product->facility;



        return view('reservations.createForProduct', compact('product', 'scheduleRules', 'reservations', 'accounts', 'selected_user', 
        'facility', 'reservation_date', 'reservation_start', 'reservation_end', 'availableStartTimes', 'availableEndTimes'));
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        dd($request->all());
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


        $price_group_id = $request->price_group_id;

        $price_group = PriceGroup::find($price_group_id);

        $price = $price_group->price;
       
        if (!$product->can_reserve) {
            return back()->withErrors(['error' => 'This product cannot be reserved.']);
        }

        

        $start = \DateTime::createFromFormat('Y-m-d H:i', $request->reservation_date . ' ' . $request->reservation_start);
        $end = \DateTime::createFromFormat('Y-m-d H:i', $request->reservation_date . ' ' . $request->reservation_end);

        

        // if (!$product->isReservable($start, $end)) {
        //     return back()->withErrors(['error' => 'The reservation does not match the product\'s schedule rules.']);
        // }
   

        // create an order to go with it, with no items
        $order = Order::create([
            'user_id' => $request->user_id,
            'facility_id' => $product->facility_id,
            'title' => $request->title,
            'date' => now(),
            'description' => $request->description,
            'status' => 'quote',
            'price_group' => $price_group->name,
            'payment_account_id' => $request->payment_account_id,
        ]);

        $order_id = $order->id;

        // calculate the minutes between the start and end times
        $start = new \DateTime($request->reservation_start);
        $end = new \DateTime($request->reservation_end);
       
        // calculate how many minutes are between the start and end times
        $interval = $start->diff($end);
        $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;


        // add order item to the order, with the product, and quantity is # of minutes
        $orderItem = OrderItem::create([
            'order_id' => $order_id,
            'product_id' => $product->id,
            'quantity' => $minutes,
            'name' => $product->name,
            'description' => 'Reservation for ' . $product->name,
            'price' => $price,
            'status' => 'pending',
        ]);

        $orderItem->quantity = $minutes;
        $orderItem->save();

        // add the orderlog to the order
        $order->logs()->create([
            'user_id' => auth()->user()->id ?? null,
            'message' => 'Order created via reservation.',
            'changed_at' => now(),
        ]);

        $order->updateTotal();


        Reservation::create([
            'product_id' => $product->id,
            'reservation_start' => \DateTime::createFromFormat('Y-m-d H:i', $request->reservation_date . ' ' . $request->reservation_start)->format('Y-m-d H:i:s'),
            'order_id' => $order_id,
            'reservation_end' => \DateTime::createFromFormat('Y-m-d H:i', $request->reservation_date . ' ' . $request->reservation_end)->format('Y-m-d H:i:s'),
        ]);



        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully. A pending order has been created.');
    }


}
