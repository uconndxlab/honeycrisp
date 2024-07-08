<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">

            <div class="order-lede mb-3">
                <h1>Order Details</h1>
                <span class="text-muted">Order ID: {{ $order->id }}</span>
            </div>

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
                    <p><strong>Customer Email:</strong> <a href="mailto:{{ $order->user->email }}">{{
                            $order->user->email }}</a>
                    </p>
                    <p><strong>Payment Account:</strong> {{ $payment_account->account_name }}
                        ({{ $payment_account->formatted() }})</p>

                    <p><strong>Price Group:</strong> {{ $order->price_group}}</p>


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
                    <p><strong>Title:</strong> {{ $order->title }}</p>
                    <p><strong>Description:</strong> {{ $order->description }}</p>
                    <p><strong>Status:</strong>
                        @if ($order->status == 'complete')
                        <span class="badge badge-success">{{ $order->status }}</span>
                        @elseif ($order->status == 'approved')
                        <span class="badge badge-primary">{{ $order->status }}</span>
                        @elseif ($order->status == 'in_progress')
                        <span class="badge badge-info">{{ $order->status }}</span>
                        @else <span class="badge badge-warning">{{ $order->status }}</span>

                        @endif

                        <!-- if order is invoice, and price_group is 'internal', display "awaiting processing in KFS" -->
                        @if ($order->status == 'invoice' && $order->price_group == 'internal')
                        <span class="badge badge-warning">Awaiting Processing in KFS</span>
                        @endif

                        <!-- if order status is reconciled and price_group is 'internal', display "reconciled in KFS" -->
                        @if ($order->status == 'reconciled' && $order->price_group == 'internal')
                        <span class="badge badge-success">Reconciled in KFS</span>
                        @endif
                    </p>

                    <p><strong>Submitted Date:</strong> {{ $order->created_at->format('m/d/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>