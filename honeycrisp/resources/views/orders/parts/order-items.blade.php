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
                            @foreach ($order->items as $item)
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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="shoppingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="shopByCategory-tab" data-bs-toggle="tab"
                        data-bs-target="#shopByCategory" type="button" role="tab" aria-controls="shopByCategory"
                        aria-selected="true">All Products</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="addCustomItem-tab" data-bs-toggle="tab" data-bs-target="#addCustomItem"
                        type="button" role="tab" aria-controls="addCustomItem" aria-selected="false">Add Custom
                        Item</button>
                </li>

                <!-- import csv option -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="importCsv-tab" data-bs-toggle="tab" data-bs-target="#importCsv"
                        type="button" role="tab" aria-controls="importCsv" aria-selected="false">Import
                        CSV</button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Shop by Category Tab -->
                <div class="tab-pane fade show active pt-3" id="shopByCategory" role="tabpanel"
                    aria-labelledby="shopByCategory-tab">

                    <!-- Category filter select -->
                    <div class="mb-3">
                        <form action="{{ route('orders.edit', $order->id) }}" method="GET"
                            hx-get="{{ route('orders.edit', $order->id) }}" hx-trigger="change" hx-push-url="true"
                            hx-swap="outerHTML" hx-select="#results_wrap" hx-target="#results_wrap">

                            <div class="form-group">
                                <select hx-trigger="change" class="form-select" id="category_select"
                                    name="categoryRequested">
                                    <option value="">Select a Category</option>
                                    @foreach ($order->facility->categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryRequested==$category ? 'selected'
                                        : '' }}>
                                        {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <!-- loop through all products -->
                        @if ($order->facility->products->count() > 0)
                        <form action="{{ route('orders.add-item') }}" hx-on::after-request="this.reset()"
                            hx-post="{{ route('orders.add-item') }}" hx-swap="outerHTML" hx-target="#order_items"
                            hx-select="#order_items" hx-reset="true" method="POST">
                            @csrf
                            <table id="products_table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->facility->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <textarea type="text" name="description"
                                                class="form-control">{{ $product->description }}</textarea>
                                        </td>
                                        <td>
                                            @if ($order->price_group == 'internal')
                                            ${{ number_format($product->unit_price_internal, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_internal }}">
                                            @elseif ($order->price_group == 'external_nonprofit')
                                            ${{ number_format($product->unit_price_external_nonprofit, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_external_nonprofit }}">
                                            @elseif ($order->price_group == 'external_forprofit')
                                            ${{ number_format($product->unit_price_external_forprofit, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_external_forprofit }}">
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="quantity" class="form-control" value="1"
                                                required>
                                        </td>
                                        <td>
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-primary">Add to
                                                Order</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                        @endif
                    </div>
  
                <div id="results_wrap">
                    <div id="recently_used">
                        <h6>Recently Used:</h6>

                        @if (count($order->facility->recently_used_products) == 0)
                        <div class="alert alert-info">No recently used products for this facility.</div>
                        @else
                        <form action="{{ route('orders.add-item') }}" hx-on::after-request="this.reset()"
                            hx-post="{{ route('orders.add-item') }}" hx-swap="outerHTML" hx-target="#order_items"
                            hx-select="#order_items" hx-reset="true" method="POST">
                            @csrf
                            <table id="products_table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- for each recentlyUsedProducts (on Facility) -->
                                    @foreach ($order->facility->recently_used_products as $product)
                                    <tr>

                                        <td>{{ $product->name }}</td>
                                        <td>

                                            <textarea type="text" name="description"
                                                class="form-control">{{ $product->description }}</textarea>

                                        </td>
                                        <td>
                                            @if ($order->price_group == 'internal')
                                            ${{ number_format($product->unit_price_internal, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_internal }}">
                                            @elseif ($order->price_group == 'external_nonprofit')
                                            ${{ number_format($product->unit_price_external_nonprofit, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_external_nonprofit }}">
                                            @elseif ($order->price_group == 'external_forprofit')
                                            ${{ number_format($product->unit_price_external_forprofit, 2) }}
                                            <input type="hidden" name="price"
                                                value="{{ $product->unit_price_external_forprofit }}">
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="quantity" class="form-control" value="1"
                                                required>
                                        </td>
                                        <td>
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-primary">Add to
                                                Order</button>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                        @endif
                    </div>
                </div>


                @if ($order->facility->products->count() == 0)
                <div class='col-md-12'>
                    <div class="alert alert-info">No products available.</div>
                </div>
                @endif
            </div>

            <!-- Add Custom Item Tab -->
            <div class="tab-pane fade pt-3" id="addCustomItem" role="tabpanel" aria-labelledby="addCustomItem-tab">
                <div class="custom-item">
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
                                <form action="{{ route('orders.add-item') }}" hx-on::after-request="this.reset()"
                                    hx-post="{{ route('orders.add-item') }}" hx-swap="outerHTML"
                                    hx-target="#order_items" hx-select="#order_items" hx-reset="true" method="POST">
                                    @csrf
                                    <td>
                                        <input type="text" name="name" class="form-control" placeholder="Custom Product"
                                            required>
                                    </td>
                                    <td>
                                        <textarea type="text" name="description" class="form-control"
                                            placeholder="Custom Product Description" required>
                                        </textarea>
                                    </td>
                                    <td>
                                        <input type="number" name="price" class="form-control"
                                            placeholder="Custom Product Price" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" class="form-control" value="1" required>
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
                </div>
            </div>
            <div class="tab-pane fade pt-3" id="importCsv" role="tabpanel" aria-labelledby="importCsv-tab">
                <div class="import-csv">
                    <form action="{{ route('orders.import-csv') }}" method="POST" enctype="multipart/form-data"
                        hx-post="{{ route('orders.import-csv') }}" hx-swap="outerHTML" hx-target="#order_items tbody"
                        hx-select="#order_items tbody">
                        @csrf
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Select CSV File</label>
                            <input class="form-control" type="file" name="csv_file" id="csv_file" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="btn btn-primary">Import CSV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>