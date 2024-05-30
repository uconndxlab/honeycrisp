<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">

            <h1>Order Details</h1>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to All Orders</a>
                <a href="javascript:print();" class="btn btn-primary">Print Order <i class="bi bi-printer"></i></a>
            </div>

            <div class="order-meta my-4">

                <h2> {{ $order->facility->name }}</h2>
                <a href="mailto:{{ $order->facility->email }}">{{ $order->facility->email }}</a>

            </div>

        </div>
    </div>

    <div class="row">
        <!-- customer details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Customer Details</h2>
                </div>
                <div class="card-body">

                    <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Customer Email:</strong> <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a>
                    </p>
                    <p><strong>Payment Account:</strong> {{ $payment_account->account_name }}
                        ({{ $payment_account->formatted() }})</p>


                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Order Details Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Order Details</h2>
                </div>
                <div class="card-body">
                    <p><strong>Order Title:</strong> {{ $order->title }}</p>
                    <p><strong>Order Description:</strong> {{ $order->description }}</p>
                    <p><strong>Order Status:</strong> {{ $order->status }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('m/d/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>