<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $status_options = Order::statusOptions();

        // if request[status] is not null, filter orders by status
        if (request('status')) {
            $orders = Order::all()->where('status', request('status'))->sortByDesc('date');
        } else {
            $orders = Order::all()->where('status', '!=','complete')->sortByDesc('date');
        }



        return view('orders.index', compact('orders', 'status_options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($facilityAbbreviation)
    {

        // if request[netid] is not null, get the user with that netid so they can be selected by default

        $users = User::all()->where('status', 'active');
        $selected_user = null;

        if (request('user_id')) {
            $user = User::all()->where('netid', request('user_id'))->first();
            $selected_user = $user->id;
            $accounts = [];

            $accounts =  $user->paymentAccounts()->get();
        } else {
            $accounts = null;
        }


        $facility = Facility::all()->where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first();

        return view('orders.create', compact('facility', 'users', 'selected_user', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'user_id' => 'required',
            'facility_id' => 'exists:facilities,id',
            'payment_account' => 'required',
            'price_group' => 'required',
        ]);

        $user_id = User::all()->where('netid', $request->user_id)->first()->id;
        $facility_id = $request->facility_id;
        $payment_account = PaymentAccount::find($request->payment_account)->id;

        $order = new Order();
        $order->user_id = $user_id;

        $order->title = $request->title;
        $order->description = $request->description;
        $order->date = $request->date;
    
        $order->facility_id = $facility_id;
        $order->payment_account = $payment_account;
        $order->status = 'quote';
        $order->price_group = $request->price_group;

        if ($request->external_company_name) {
            $order->company_name = $request->external_company_name;
        }
        
        $order = $order->save();
    
        return redirect()->route('orders.edit', $order)->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order_items = OrderItem::all()->where('order_id', $order->id);
        $payment_account = PaymentAccount::find($order->payment_account);
        
        $order->total = 0;
        foreach ($order_items as $order_item) {
            $order->total += $order_item->price * $order_item->quantity;
        }

        return view('orders.show', compact('order', 'order_items', 'payment_account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order = Order::find($order->id);
        $facility = Facility::find($order->facility_id);
        $users = User::all()->where('status', 'active');
        $selected_user = $order->user_id;
        $status_options = Order::statusOptions();

        $current_account = PaymentAccount::find($order->payment_account);
        // check to see if the expiration date of the account is coming up
        $expiration_date = $current_account->expiration_date;
        $today = date('Y-m-d');
        $days_until_expiration = (strtotime($expiration_date) - strtotime($today)) / (60 * 60 * 24);

        if ($days_until_expiration < 30) {
            $account_warning = 'This account will expire in ' . $days_until_expiration . ' days.';
        } else {
            $account_warning = null;
        }

        if (request('user_id') or $order->user_id) {
            if (request('user_id')) {
                $user = User::all()->where('netid', request('user_id'))->first();
            } else {
                $user = User::find($order->user_id);
            }

            $selected_user = $user->id;
            $accounts = [];

            $accounts =  $user->paymentAccounts()->get();
        } else {
            $accounts = null;
        }

        
        return view('orders.edit', compact('order', 'facility', 'users', 'selected_user', 'accounts', 'account_warning', 'status_options'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
       
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'user_id' => 'required',
            'facility_id' => 'exists:facilities,id',
            'payment_account' => 'required',
            'status' => 'required',
            'price_group' => 'required',
        ]);

        $user_id = User::all()->where('netid', $request->user_id)->first()->id;
        $facility_id = $request->facility_id;
        $payment_account = PaymentAccount::find($request->payment_account)->id;

        $order->user_id = $user_id;

        $order->title = $request->title;
        $order->description = $request->description;
        $order->date = $request->date;
        $order->facility_id = $facility_id;
        $order->payment_account = $payment_account;
        $order->status = $request->status;
        $order->price_group = $request->price_group;

        if ($request->external_company_name) {
            $order->company_name = $request->external_company_name;
        }

        $order->save();

        return redirect()->route('orders.edit', $order)->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'quantity' => 'required',
        ]);

        $order_item = new OrderItem();
        if ($request->product_id == 0) {
            $order_item->name = $request->name;

        } else {
            $order_product = Product::find($request->product_id);
            $order_item->product_id = $request->product_id;
            $order_item->name = $order_product->name;
        }
        $order_item->price = $request->price;
        $order_item->order_id = $request->order_id;
        $order_item->quantity = $request->quantity;

        $order_item->description = $request->description;

        $order_item->save();

        $order = Order::find($request->order_id);
        $order->updateTotal();

        return redirect()->route('orders.edit', $request->order_id)->with('success', 'Item added to order successfully!');
    }

    public function removeItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'order_item_id' => 'required',
        ]);
        $order = Order::find($request->order_id);
        $order_item = OrderItem::find($request->order_item_id);
        $order_item->delete();

        $order->updateTotal();

        return redirect()->route('orders.edit', $request->order_id)->with('success', 'Item removed from order successfully!');
    }
}
