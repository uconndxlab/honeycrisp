<!-- List of current items in the order -->
<div class="container my-5">


    <div class="row">
        <!-- Add Items Button -->
        <button class="btn btn-primary my-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProducts"
            aria-controls="offcanvasProducts">
            Add Items
        </button>

        <!-- Offcanvas for Available Products -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasProducts" aria-labelledby="offcanvasProductsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasProductsLabel">Available Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="shoppingTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="shopByCategory-tab" data-bs-toggle="tab"
                            data-bs-target="#shopByCategory" type="button" role="tab"
                            aria-controls="shopByCategory" aria-selected="true">
                            All Products
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="addCustomItem-tab" data-bs-toggle="tab"
                            data-bs-target="#addCustomItem" type="button" role="tab" aria-controls="addCustomItem"
                            aria-selected="false">
                            Add Custom Item
                        </button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content mt-3">
                    <!-- Shop by Category Tab -->
                    <div class="tab-pane fade show active" id="shopByCategory" role="tabpanel"
                        aria-labelledby="shopByCategory-tab">
                        <div class="mb-3">
                            <form action="{{ route('orders.edit', $order->id) }}" method="GET"
                                hx-get="{{ route('orders.edit', $order->id) }}" hx-trigger="change" hx-push-url="true"
                                hx-swap="outerHTML" hx-select="#facility_products" hx-target="#facility_products">
                                <div class="form-group">
                                    <select hx-trigger="change" class="form-select" id="category_select"
                                        name="categoryRequested">
                                        <option value="">Recently Used</option>
                                        @foreach ($order->facility->categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $categoryRequested == $category ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>



                          
                          



                            <div id="facility_products" class="mt-4">

                            {{-- little item search --}}

                            <form onsubmit="return false;" action="{{ route('orders.edit', $order->id) }}" method="GET">
                                <input type="hidden" name="categoryRequested" value="{{ request('categoryRequested') }}">
                                <div class="input-group mb-3 mt-3">
                                    <input class="form-control" type="text" id="product_search" name="product_search" 
                                           placeholder="Search for a product" aria-label="Product Search" 
                                           aria-describedby="product_search"
                                           hx-get="{{ route('orders.edit', $order->id) }}"
                                           hx-trigger="keyup changed delay:500ms"
                                           hx-target="#facility_products"
                                           hx-select="#facility_products"
                                           hx-swap="outerHTML"
                                           value="{{ request('product_search') }}"
                                           hx-vals="javascript:{'categoryRequested': '{{ request('categoryRequested') }}'}">
                                </div>
                            </form>

                                @if ($facility_products->count() > 0)

                                    <ul class="list-group">

                                        @foreach ($facility_products as $product)
                                            <li class="list-group-item">
                                                <div class="cursor-pointer d-flex justify-content-between align-items-center"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#productForm{{ $product->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="productForm{{ $product->id }}">
                                                    <a href="javascript:void(0);">
                                                        {{ $product->name }}
                                                    </a>
                                                    <i class="bi bi-chevron-down"></i>
                                                </div>
                                                <div class="collapse mt-3" id="productForm{{ $product->id }}">
                                                    <form hx-post="{{ route('orders.add-item') }}"
                                                        hx-target="#order_items" hx-swap="outerHTML"
                                                        hx-select="#order_items"
                                                        action="{{ route('orders.add-item') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-2">
                                                            <label for="price_group_{{ $product->id }}"
                                                                class="form-label">Price Group</label>
                                                            <select id="price_group_{{ $product->id }}"
                                                                name="price" class="form-select">
                                                                @foreach ($product->priceGroups as $priceGroup)
                                                                    <option value="{{ $priceGroup->price }}">
                                                                        {{ $priceGroup->name }} ($@dollars($priceGroup->price))
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="description_{{ $product->id }}"
                                                                class="form-label">Description</label>
                                                            <textarea id="description_{{ $product->id }}" name="description" class="form-control" placeholder="Description">{{ $product->description }}</textarea>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="quantity_{{ $product->id }}"
                                                                class="form-label">Quantity</label>
                                                            <input id="quantity_{{ $product->id }}" type="number"
                                                                step="0.5" name="quantity" class="form-control"
                                                                value="1" required>
                                                        </div>

                                                        <input type="hidden" name="order_id"
                                                            value="{{ $order->id }}">
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Add
                                                            to Order</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="alert alert-info mt-3">No products available.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Add Custom Item Tab -->
                    <div class="tab-pane fade" id="addCustomItem" role="tabpanel"
                        aria-labelledby="addCustomItem-tab">
                        <div class="mt-3">
                            <form action="{{ route('orders.add-item') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Custom Product Name" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="description" class="form-control" placeholder="Description" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="price" class="form-control" placeholder="Price"
                                        step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="quantity" class="form-control"
                                        placeholder="Quantity" value="1" required>
                                </div>
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button type="submit" class="btn btn-primary">Add Custom Item</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
                                        <td>$@dollars($item->price)
                                        </td>
                                        <td>$@dollars($item->quantity * $item->price)</td>
                                        <td>
                                            <form hx-post="{{ route('orders.remove-item') }}"
                                                hx-target="#order_items" hx-swap="outerHTML" hx-select="#order_items"
                                                action="{{ route('orders.remove-item') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="order_item_id"
                                                    value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-start"><strong>Order Total:</strong></td>
                                    <td class="">
                                        $@dollars($order->total)
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>


    </div>

    <style>
        /* cursor pointer for the collapsybois */
        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-pointer:hover {
            text-decoration: underline;
        }
    </style>

    <script>
        // toggle the chevron icon on collapse
        const collapseElements = document.querySelectorAll('.collapse');

        collapseElements.forEach((collapseElement) => {
            collapseElement.addEventListener('show.bs.collapse', () => {
                const chevron = collapseElement.previousElementSibling.querySelector('.bi');
                chevron.classList.remove('bi-chevron-down');
                chevron.classList.add('bi-chevron-up');
            });

            collapseElement.addEventListener('hide.bs.collapse', () => {
                const chevron = collapseElement.previousElementSibling.querySelector('.bi');
                chevron.classList.remove('bi-chevron-up');
                chevron.classList.add('bi-chevron-down');
            });
        });

        // autofocus on the product search input when the offcanvas is shown
        const offcanvasProducts = document.getElementById('offcanvasProducts');
        offcanvasProducts.addEventListener('shown.bs.offcanvas', () => {
            const productSearchInput = document.getElementById('product_search');
            productSearchInput.focus();
        });
    </script>
