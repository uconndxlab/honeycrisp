<!-- List of current items in the order -->
<div class="container my-5">
    <div class="row">
        <div id="order_items" class="col-md-12">
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
                                    <td>{{ $item->name }} <br>
                                        <small class="text-muted">{{ $item->description }}</small>
                                    </td>
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

        <div id="available_products" class="col-md-12 my-3">
            <div class="row">
                    <div class="col-md-12">
                        <h3> Add a Custom Item:</h3>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row for a custom product -->
                                <tr id="custom_product_row">
                                    <form action="{{ route('orders.add-item') }}" 
                                    hx-on::after-request="this.reset()"
                                    hx-post="{{ route('orders.add-item') }}"
                                    hx-swap="outerHTML"
                                    hx-target="#order_items"
                                    hx-select="#order_items"
                                    hx-reset="true"
                                    method="POST">
                                        @csrf
                                    <td>
                                        <input type="text" name="name" class="form-control" placeholder="Custom Product" required>
                                    </td>
                                    <td>
                                        <textarea type="text" name="description" class="form-control" placeholder="Custom Product Description" required>
                                        </textarea>
                                    </td>
                                    <td>
                                        
                                        <input type="number" name="price" class="form-control" placeholder="Custom Product Price" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" class="form-control" value="1"
                                        required>
                                    </td>
                                    <td>

                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="product_id" value="0">

                                            <button type="submit" class="btn btn-primary">Add to Order</button>
                                    </td>
                                </form>

                                </tr>
                            </tbody>

                        </table>

                        <h3> {{ $order->facility->name }} Products:</h3>

                        <!-- cattegory filter dropdown button -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter by Category
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">All</a></li>
                                @foreach($order->facility->categories as $category)
                                    <li><a class="dropdown-item" href="#">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <table id = "products_table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody

                                @foreach($order->facility->products as $product)
                                    <tr>
                                        <form action="{{ route('orders.add-item') }}" method="POST"
                                        hx-post="{{ route('orders.add-item') }}"
                                        hx-swap="outerHTML"
                                        hx-target="#order_items tbody"
                                        hx-select="#order_items tbody"
                                        >

                                            @csrf
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <textarea type="text" name="description" class="form-control">{{ $product->description }}</textarea>

                                        </td>
                                        <td>
                                            <!-- if price group is internal, show internal price.
                                                if price gorup is external_non_profit, show external non profit price.
                                                if price group is external_for_profit, show external for profit price.
                                            -->
                                            @if ($order->price_group == 'internal')
                                                ${{ number_format($product->unit_price_internal, 2) }}
                                                <input type="hidden" name="price" value="{{ $product->unit_price_internal }}">
                                            @elseif ($order->price_group == 'external_nonprofit')
                                                ${{ number_format($product->unit_price_external_nonprofit, 2) }}
                                                <input type="hidden" name="price" value="{{ $product->unit_price_external_nonprofit }}">
                                            @elseif ($order->price_group == 'external_forprofit')
                                                ${{ number_format($product->unit_price_external_forprofit, 2) }}
                                                <input type="hidden" name="price" value="{{ $product->unit_price_external_forprofit }}">
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="quantity" class="form-control" value="1"
                                            required>
                                        </td>
                                        <td>

                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                

                                                <button type="submit" class="btn btn-primary">Add to Order</button>

                                        </td>
                                    </form>
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

    <script>

        // after a form is submitted, reset the form
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    form.reset();
                });
            });
        });

    </script>
