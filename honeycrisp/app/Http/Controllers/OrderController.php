<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderLog;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account_types = PaymentAccount::types();
        $status_options = Order::statusOptions();
        $selected_status = request('status') ?? null;
        $start_date = request('start_date') ?? null;
        $end_date = request('end_date') ?? null;
        $facility_id = request('facility_id') ?? null;
        $price_group = request('price_group') ?? null;
        $account_type = request('account_type') ?? null;
        $data = [];
    
        // Build the query
        $query = Order::query();
    
        // Filter by status
        if ($selected_status) {
            $query->where('status', $selected_status);
        }
    
        // Filter by start date
        if ($start_date) {
            $query->whereDate('date', '>=', $start_date);
        }
    
        // Filter by end date
        if ($end_date) {
            $query->whereDate('date', '<=', $end_date);
        }
    
        // Filter by facility id
        if ($facility_id) {
            $query->where('facility_id', $facility_id);
            $data['facility'] = Facility::find($facility_id);
        }
    
        // Filter by price group
        if ($price_group) {
            $query->where('price_group', $price_group);
        }
    
        // Filter by account type (only orders with users who have payment accounts of that type)
        if ($account_type) {
            // where order->paymentAccount->account_type == $account_type
            $query->whereHas('paymentAccount', function ($q) use ($account_type) {
                $q->where('account_type', $account_type);
            });

        }
    
        // Process search query for netid, order title, order id
        if (request('search')) {
            $search = request('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('netid', 'like', '%' . $search . '%');
                      $q->orWhere('name', 'like', '%' . $search . '%');
                  });
        }
    
        // Get the filtered orders
        $orders = $query->where('status', '!=', 'complete')
                        ->orderBy('date')
                        ->paginate(30);
    
        return view('orders.index', compact('orders', 'status_options', 'selected_status', 'data', 'account_types'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create($facilityAbbreviation)
    {

        // if request[netid] is not null, get the user with that netid so they can be selected by default

        //$users = User::all()->where('status', 'active');
        $selected_user = null;

        if (request('netid')) {
            $user = User::all()->where('netid', request('netid'))->first();
            $selected_user = $user;
            $accounts = [];

            $accounts = $user->paymentAccounts()->where('expiration_date', '<', now())->get();
        } 

        elseif (request('user_id')) {
            $user = User::find(request('user_id'));
            $selected_user = $user;
            $accounts = [];

      
            
            // get the payment accounts for the user that are not expired
            $accounts = $user->paymentAccounts()->where('expiration_date', '>', now())->get();

        }
        
        else {
            $accounts = null;
        }


        $facility = Facility::all()->where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first();

        return view('orders.create', compact('facility', 'selected_user', 'accounts'));
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

            // payment_account_id is only reqired if price_group is 'internal'
            'payment_account_id' => $request->price_group == 'internal' ? 'required' : '',
            
            'price_group' => 'required',
        ]);

        $user_id = $request->user_id;
        $facility_id = $request->facility_id;



        $order = new Order();
        $order->user_id = $user_id;

        $order->title = $request->title;
        $order->description = $request->description;
        $order->date = $request->date;
    
        $order->facility_id = $facility_id;
        if($request->price_group == 'internal'){
            $payment_account = PaymentAccount::find($request->payment_account_id)->id;
            $order->payment_account_id = $payment_account;
        }

        $order->mailing_address = $request->mailing_address;
        $order->purchase_order_number = $request->purchase_order_number;
        $order->status = 'quote';
        $order->price_group = $request->price_group;

        if ($request->external_company_name) {
            $order->company_name = $request->external_company_name;
        }
        
        $order = $order->save();
        // get the id of the order that was just created
        $order = Order::latest()->first();
        
        OrderLog::create([
            'order_id' => $order->id,
            'message' => 'Order created.',
            'user_id' => auth()->user()->id ?? null,
            'changed_at' => now(),
        ]);
        
    
        return redirect()->route('orders.edit', $order)->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order_items = OrderItem::all()->where('order_id', $order->id);
        $payment_account = PaymentAccount::find($order->payment_account_id);
        
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

        if($order->price_group == 'internal'){
            $accounts = PaymentAccount::all()->where('user_id', $order->user_id);
        } else {
            $accounts = null;
        }
      
        if (request('categoryRequested')) {
            $categoryRequested = Category::find(request('categoryRequested'))->get();
            
        } else {
            // else, get all categories for the facility and set $categoryRequested to all categories
            $categoryRequested = null;
            
        }

        //handling for internal-only orders

        if ($order->price_group == 'internal') {
            $current_account = PaymentAccount::find($order->payment_account_id);
            // check to see if the expiration date of the account is coming up
            $expiration_date = $current_account->expiration_date;
            $today = date('Y-m-d');
            $days_until_expiration = (strtotime($expiration_date) - strtotime($today)) / (60 * 60 * 24);
    
    
            $account_warning = null;
    
           if ($days_until_expiration <= 0) {
                $account_warning = 'This account has expired.';
                $warning_type = 'danger';
            } 
            
            
    
           else if ($days_until_expiration < 30) {
                $account_warning = 'This account will expire in ' . $days_until_expiration . ' days.';
                $warning_type = 'warning';
            } 
            
            else {
                $account_warning = null;
            }
    
            if($account_warning){
                $account_warning_array = array('warning' => $account_warning, 'type' => $warning_type);
            } else {
                $account_warning_array = null;
            }
        } else {
            $accounts = null;
            $account_warning_array = null;
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

        
        return view('orders.edit', compact('order', 'facility', 'users', 'selected_user', 'accounts', 'account_warning_array', 'status_options', 'categoryRequested'));
    }

    public function export(Request $request)
    {
        // Retrieve orders based on current query parameters
        $orders = Order::query();
    
        if ($request->has('status')) {
            $orders->where('status', $request->status);
        }
        
        if ($request->has('facility_id')) {
            $orders->where('facility_id', $request->facility_id);
        }
        
        if ($request->has('search')) {
            $orders->where(function ($query) use ($request) {
                $query->where('netid', 'like', '%' . $request->search . '%')
                      ->orWhere('title', 'like', '%' . $request->search . '%')
                      ->orWhere('id', $request->search);
            });
        }
    
        if ($request->has('start_date') && $request->has('end_date')) {
            $orders->whereBetween('date', [$request->start_date, $request->end_date]);
        }
    
        $orders = $orders->get();
    
        // Prepare CSV export
        $csvFileName = 'orders_' . now()->format('Ymd') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Set headers for the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
        
        // Add the CSV header row
        fputcsv($handle, ['ID', 'Facility', 'User', 'Title', 'Date', 'Status', 'Total']);
    
        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->id,
                $order->facility->abbreviation,
                $order->user->name,
                $order->title,
                $order->date,
                $order->status,
                $order->total,
            ]);
        }
    
        fclose($handle);
        exit();
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

            // payment_account_id is only reqired if price_group is 'internal'
            'payment_account_id' => $request->price_group == 'internal' ? 'required' : '',

            'status' => 'required',
            'price_group' => 'required',
        ]);

        $user_id = $request->user_id;
        $facility_id = $request->facility_id;


        $order->user_id = $user_id;

        $fields_changed = [];

        if ($order->title != $request->title) {
            $fields_changed[] = 'title';
        }

        if ($order->description != $request->description) {
            $fields_changed[] = 'description';
        }

        if ($order->date != $request->date) {
            $fields_changed[] = 'date';
        }


        if ($order->payment_account_id != $request->payment_account_id) {
            $fields_changed[] = 'payment account';
        }

        if ($order->status != $request->status) {
            $fields_changed[] = 'status';
        }

        if ($order->price_group != $request->price_group) {
            $fields_changed[] = 'price group';
        }

        // user_id
        if ($order->user_id != $user_id) {
            $fields_changed[] = 'user';
        }

    

        $order->title = $request->title;
        $order->description = $request->description;
        $order->date = $request->date;
        $order->facility_id = $facility_id;

        if($request->price_group == 'internal'){
            $payment_account = PaymentAccount::find($request->payment_account_id)->id;
            $order->payment_account_id = $payment_account;
        }

        $message = 'Order updated. Fields changed: ' . implode(', ', $fields_changed);

        // if the field changed is status, add a message to the log
        if (in_array('status', $fields_changed)) {
            $message .= '. Status changed to ' . $request->status;
        }

        
        $order->status = $request->status;

        OrderLog::create([
            'order_id' => $order->id,
            'message' => $message,
            'user_id' => auth()->user()->id ?? null,
            'changed_at' => now(),
        ]);

        $order->mailing_address = $request->mailing_address;
        $order->purchase_order_number = $request->purchase_order_number;

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


        // if is_custom is true, the price will be in dollars, so convert it to cents
        if ($request->is_custom) {
            $order_item->price = $order_item->price * 100;
        }

        $order_item->order_id = $request->order_id;
        $order_item->quantity = $request->quantity;

        $order_item->description = $request->description;

        OrderLog::create([
            'order_id' => $request->order_id,
            'message' => 'Item added to order: ' . $order_item->name,
            'user_id' => auth()->user()->id ?? null,
            'changed_at' => now(),
        ]);

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

        OrderLog::create([
            'order_id' => $request->order_id,
            'message' => 'Item removed from order: ' . $order_item->name,
            'user_id' => auth()->user()->id ?? null,
            'changed_at' => now(),
        ]);

        return redirect()->route('orders.edit', $request->order_id)->with('success', 'Item removed from order successfully!');
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'csv_file' => 'required|file',
        ]);

        $order_id = $request->order_id;
        $csv_file = $request->file('csv_file');

        $file = fopen($csv_file->getPathname(), 'r');
        $data = fgetcsv($file);

        while (($data = fgetcsv($file)) !== false) {
            $order_item = new OrderItem();
            $order_item->order_id = $order_id;
            $order_item->name = $data[0];
            $order_item->price = $data[1];
            $order_item->quantity = $data[2];
            $order_item->description = $data[3];
            $order_item->save();
        }

        $order = Order::find($order_id);
        $order->updateTotal();

        return redirect()->route('orders.edit', $order_id)->with('success', 'Items imported successfully!');
    }

    public function sendToCustomer(Order $order)
    {

        OrderLog::create([
            'order_id' => $order->id,
            'message' => 'Order sent to customer.',
            'user_id' => auth()->user()->id ?? null,
            'changed_at' => now(),
        ]);

        $order->status = 'pending';
        $order->save();

        return redirect()->route('orders.edit', $order)->with('success', 'Order sent to customer successfully!');
    }
}
