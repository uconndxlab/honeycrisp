<!-- List of current items in the order -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h2>Current Items in the Order:</h2>
            @if ($order->items->count() > 0)
                <ul>
                    @foreach ($order->items as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">No items in the order.</div>
            @endif
        </div>

        <!-- availabe products -->

        <div class="col-md-12">
            <h2>Available Products:</h2>
            <div class="row">
                @foreach ($order->facility->products as $product)
                    <div class="col-md-4">
                        <div class="card my-2">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">${{ $product->unit_price }}</p>
                                <form action="{{ route('orders.add-item') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="order_id" value="{{ $order->id }}">  
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="number" name="quantity" class="form-control" value="1" required>
                                    <button type="submit" class="btn btn-primary">Add to Order</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($order->facility->products->count() == 0)
                    <div class ='col-md-12'>
                        <div class="alert alert-info">No products available.</div>
                    </div>
                @endif
            </div>
        </div>


    </div>
