<!-- List of current items in the order -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Order Items</h2>
                </div>
                <div class="card-body">
                    @if ($order->items->count() == 0)
                        <div class="alert alert-info">No items in this order.
                        </div>
                    @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('orders.remove-item') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-start"><strong>Order Total:</strong></td>
                                <td class="">
                                    ${{ number_format($order->total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- available products -->

        <div class="col-md-12 my-3">
            <h2>Available Products:</h2>
            <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Add to Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row for a custom product -->
                                <tr>
                                    <td>
                                        <input type="text" name="custom_product" class="form-control" placeholder="Custom Product" required>
                                    </td>
                                    <td>
                                        <input type="text" name="custom_product_description" class="form-control" placeholder="Custom Product Description" required>
                                    </td>
                                    <td>
                                        <input type="number" name="custom_product_price" class="form-control" placeholder="Custom Product Price" required>
                                    </td>
                                    <td>
                                        <form action="{{ route('orders.add-item') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="product_id" value="0">
                                            <div class="form-group
                                                my-3">
                                                <label for="quantity">Quantity:</label>
                                                <input type="number" name="quantity" class="form-control" value="1"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add to Order</button>
                                        </form>
                                    </td>
                                </tr>

                                @foreach($order->facility->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>${{ $product->unit_price }}</td>
                                        <td>
                                            <form action="{{ route('orders.add-item') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="form-group my-3">
                                                    <label for="quantity">Quantity:</label>
                                                    <input type="number" name="quantity" class="form-control" value="1"
                                                        required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Add to Order</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @if ($order->facility->products->count() == 0)
                    <div class ='col-md-12'>
                        <div class="alert alert-info">No products available.</div>
                    </div>
                @endif
            </div>
        </div>


    </div>
