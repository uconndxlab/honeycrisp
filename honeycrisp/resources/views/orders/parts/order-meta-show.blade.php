<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">

            <div class="order-lede mb-3">
                <h1>Core Facility Order Details</h1>
                <span class="text-muted">Order ID: {{ $order->id }}</span>
            </div>

            <div class="d-flex justify-content-between align-items-center no-print">
                <div></div>
                <a href="javascript:print();" class="btn btn-primary no-print">Print Order <i class="bi bi-printer"></i></a>
            </div>

            <div class="order-meta my-4">

                <h2>{{ $order->facility->name }}</h2>
                <a href="mailto:{{ $order->facility->email }}">{{ $order->facility->email }}</a>

            </div>

        </div>
    </div>

    <div class="row">
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

                    @if ( $order->kfs_export_id )
                    <p><strong>KFS Export:</strong> <a href="{{ route('exports.show', $order->kfs_export_id)}}">{{ $order->kfs_export_id }}</a></p>
                    @endif
                </div>
            </div>
        </div>
        <!-- customer details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Customer Details</h2>
                </div>
                <div class="card-body">

                    <p><strong>Customer Name:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Customer Email:</strong> <a href="mailto:{{ $order->customer->email }}">{{
                            $order->customer->email }}</a>
                    </p>

                    <!-- if there are more users in the users object, display the additional users -->
                    @if (count($order->users) > 1)
                    <p><strong>Additional Users:</strong>
                        @foreach ($order->users as $user)
                        @if ($user->id != $order->user->id)
                        <br>{{ $user->name }} - <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        @endif
                        @endforeach
                    </p>
                    @endif

                    @if ($order->payment_account_id)
                    <p><strong>Payment Account:</strong> {{ $payment_account->account_name }}
                        ({{ $payment_account->formatted() }})</p>
                    @endif

                    <p><strong>Price Group:</strong> {{ $order->price_group}}</p>

                    <!-- if price group is not internal, display the external organization and account number -->
                    @if ($order->price_group != 'internal' && $order->customer->external_organization)
                    <p><strong>External Organization:</strong> {{ $order->external_organization ?? $order->customer->external_organization }}</p>
                    <p><strong>External Account Number:</strong> {{ $order->external_customer_id ?? $order->customer->external_customer_id }}</p>
                    {{-- mailing_address --}}
                    @endif

                    @if ($order->mailing_address)
                    <p><strong>Mailing Address:</strong> {{ $order->mailing_address }}</p>
                    @endif


                </div>
            </div>
        </div>

    </div>
</div>