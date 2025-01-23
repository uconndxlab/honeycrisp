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
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Mail\OrderSentToCustomer;
use App\Mail\UCHInvoiceCreated;
// use log
use Illuminate\Support\Facades\Log;



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
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('netid', 'like', '%' . $search . '%');
                      $q->orWhere('name', 'like', '%' . $search . '%');
                  });
        }

        // if there is no selected status, show only quotes
        if (!$selected_status) {
            $query->where('status', 'quote');
            $selected_status = 'quote';
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

        $selected_user = null;

        if (request('netid')) {
            $user = User::where('netid', request('netid'))->first();
            $selected_user = $user;
            $accounts = [];


            // get all accounts that are not expired, or expired within the last 30 days
            $accounts = $user->paymentAccounts()->where('expiration_date', '>', now()->addDays(-30))->get();

        } 

        elseif (request('user_id')) {
            $user = User::find(request('user_id'));
            $selected_user = $user;
            $accounts = [];
            // get the payment accounts for the user that are not expired
            $accounts = $user->paymentAccounts()->where('expiration_date', '>', now()->addDays(-90))->get();

        }
        
        else {
            $accounts = null;
        }

        if (request('payment_account_id')) {
            $account_warning_array = [];
            $selected_account= PaymentAccount::find(request('payment_account_id'));
            // if the selected account expired 30 or fewer days ago, show a warning
            if ($selected_account->expiration_date < now()) {
                $days_until_expiration = (strtotime($selected_account->expiration_date) - strtotime(now())) / (60 * 60 * 24);
                if ($days_until_expiration <= 30) {
                    $account_warning = 'This account expired ' . abs($days_until_expiration) . ' days ago. You can use this account for this order, but it is recommended to use a different account.';
                    $warning_type = 'danger';

                    $account_warning_array = array('warning' => $account_warning, 'type' => $warning_type);
                }

                // if the account will expire in 30 or fewer days, show a warning
            } else if ($selected_account->expiration_date < now()->addDays(30)) {
                $days_until_expiration = (strtotime($selected_account->expiration_date) - strtotime(now())) / (60 * 60 * 24);
                $days_until_expiration = round($days_until_expiration);
                $account_warning = 'This account will expire in ' . $days_until_expiration . ' days.';
                $warning_type = 'warning';

                $account_warning_array = array('warning' => $account_warning, 'type' => $warning_type);

                
            }
        } else {
            $selected_account = null;
            $account_warning_array = null;
        }


        $facility = Facility::where('status', 'active')->where('abbreviation', $facilityAbbreviation)->first(); 

        return view('orders.create', compact('facility', 'selected_user', 'accounts', 'selected_account', 'account_warning_array'));
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

        $order->users()->sync($request->additional_users);

        
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
        $order_items = OrderItem::where('order_id', $order->id)->get();
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

        $selected_user = $order->user_id;
        $status_options = Order::statusOptions();

        if($order->price_group == 'internal'){
            $accounts = PaymentAccount::where('user_id', $order->user_id)->get();
        } else {
            $accounts = null;
        }
        if (request('categoryRequested')) {
            $categoryRequested = Category::find(request('categoryRequested'));

            // if request('product_search') is not null, filter the products by that search term
            if (request('product_search')) {
                $facility_products = $categoryRequested->products()
                    ->where('facility_id', $facility->id)
                    ->where('is_deleted', 0)
                    ->where('name', 'like', '%' . request('product_search') . '%')
                    ->get();
            } else {
                // Else, get all products associated with the category
                $facility_products = $categoryRequested->products()
                    ->where('facility_id', $facility->id)
                    ->where('is_deleted', 0)
                    ->get();
            }
    

            
        } else {
            // Else, get all categories for the facility and group products by category
            $categoryRequested = null;

            // if request('product_search') is not null, filter the products by that search term
            if (request('product_search')) {
                $facility_products = Product::where('facility_id', $facility->id)
                    ->where('is_deleted', 0)
                    ->where('name', 'like', '%' . request('product_search') . '%')
                    ->get();
            } else {
                // get the 5 most recently added products from the facility
                $facility_products = Product::where('facility_id', $facility->id)
                    ->where('is_deleted', 0)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
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
                $account_warning = 'This account expired ' . abs($days_until_expiration) . ' days ago. You can use this account for this order, but it is recommended to use a different account.';
                $warning_type = 'danger';
            } 
            
            
    
           else if ($days_until_expiration < 60) {
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
                $user = User::where('netid', request('user_id'))->first();
            } else {
                $user = User::find($order->user_id);
            }

            $selected_user = $user->id;
            $accounts = [];

            $accounts =  $user->paymentAccounts()->get();
            
        } else {
            $accounts = null;
        }

        
        return view('orders.edit', compact('order', 'facility', 'selected_user', 'accounts', 'account_warning_array', 'status_options', 'categoryRequested', 'facility_products'));
    }

    // show the financial files for an order

    public function financialFiles(Request $request) {
       
        $order = Order::find($request->order);
        $payment_account = PaymentAccount::find($order->payment_account_id);
        return view('orders.financialFiles', compact('order', 'payment_account'));

    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required',
            'status' => 'required',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $fields_changed = [];

        foreach ($orders as $order) {
            if ($order->status != $request->status) {
                $fields_changed[] = 'status';
            }

            $order->status = $request->status;
            $order->save();

            OrderLog::create([
                'order_id' => $order->id,
                'message' => 'Order status changed to ' . $request->status,
                'user_id' => auth()->user()->id ?? null,
                'changed_at' => now(),
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Orders updated successfully!');
    }

    public function export(Request $request)
    {
        // Check if specific order IDs are provided
        if ($request->has('order_ids')) {
            $orders = Order::with(['facility', 'customer'])
                ->whereIn('id', $request->order_ids)
                ->get();
        } else {
            // If no specific IDs are passed, fetch all orders (fallback)
            $orders = Order::with(['facility', 'customer'])->get();
        }
    
        // Prepare CSV export
        $csvFileName = 'orders_' . now()->format('Ymd') . '.csv';
    
        // Use Laravel's response handling for streaming CSV
        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
    
            // Add CSV header row
            fputcsv($handle, ['ID', 'Facility', 'User', 'Title', 'Date', 'Status', 'Account', 'Total']);
    
            // Write data for each order
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    optional($order->facility)->abbreviation,
                    optional($order->customer)->name,
                    $order->title,
                    $order->date,
                    $order->status,
                    $order->paymentAccount->formatted(),
                    $order->total,
                ]);
            }
    
            fclose($handle);
        }, $csvFileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ]);
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
            $fields_changed[] = 'customer';
        }

        // compare $order->users to $request->additional_users, remember $order->users is a collection of User objects
        $users = $order->users->pluck('id')->toArray();
        $additional_users = $request->additional_users ?? [];
        if ($users != $additional_users) {
            $fields_changed[] = 'users';
        }

        $order->title = $request->title;
        $order->description = $request->description;
        $order->date = $request->date;
        $order->facility_id = $facility_id;
        $order->users()->sync($additional_users);

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

        if(!empty($fields_changed)){
            OrderLog::create([
                'order_id' => $order->id,
                'message' => $message,
                'user_id' => auth()->user()->id ?? null,
                'changed_at' => now(),
            ]);
        }

        $order->mailing_address = $request->mailing_address;
        $order->purchase_order_number = $request->purchase_order_number;

        $order->price_group = $request->price_group;

        if ($request->external_company_name) {
            $order->company_name = $request->external_company_name;
        }

        $order->save();

        // if the payment_account type is uch, and the status has been switched to invoice, send the order to the UCH finance team
        if ($order->paymentAccount->account_type === 'uch' && $order->status === 'invoice') {
            try {
                Mail::to(env('UCH_FINANCE_EMAIL'))->send(new UCHInvoiceCreated($order));
            } catch (\Exception $e) {
                // Optionally, log the error if email fails to send
                Log::error('Failed to send UCH invoice email: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.edit', $order)->with('success', 'Order updated successfully!')->withFragment('order_items');
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

        if ($order->customer && $order->customer->email) {
            Mail::to($order->customer->email)->cc($order->users->pluck('email'))->send(new OrderSentToCustomer($order));
        }

        return redirect()->route('orders.edit', $order)->with('success', 'Order sent to customer successfully!');
    }

    public function downloadFinancialFile($order){
        // put the results of $order->financialFile into a .dat file and download it
        $order = Order::find($order);
        $financial_file = $order->financialFile();

        // the output is just a sstring, so we can just put it in a file
        $fileName = 'honeycrisp-' . $order->id . '.dat';
        $filePath = storage_path('app/exports/' . $fileName);

        if ( !file_exists($filePath) && $financial_file ) {
            file_put_contents($filePath, $financial_file);


            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
  
            file_put_contents($filePath, $financial_file);
            return response()->download($filePath)->deleteFileAfterSend(true);
        }
        
    }
}
