<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="../home/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Track your
                            Order</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="mx-xl-10">
            <div class="mb-6 text-center">
                <h1 class="mb-6">Track Your Order</h1>
                <p class="text-gray-90 px-xl-10">
                    Enter your Order ID and the Email you used during checkout to track your order.
                </p>
            </div>

            <div class="my-4 my-xl-8">
                <!-- Order Tracking Form -->
                <form action="{{ route('order.trackOrder') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <!-- Order ID Field -->
                            <div class="form-group">
                                <label class="form-label" for="order_code">Order ID</label>
                                <input type="text" class="form-control" name="order_code" id="order_code"
                                    placeholder="Enter your Order ID" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col mb-1">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">
                                Track Order
                            </button>
                        </div>
                    </div>
                </form>
                <!-- End Order Tracking Form -->

                <!-- Order Details Section -->
                @if (isset($order))
                    <h3>Order Details</h3>
                    <p><strong>Order Code:</strong> {{ $order->code }}</p>
                    <p><strong>Status:</strong>
                        @if ($order->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif ($order->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif ($order->status === 'canceled')
                            <span class="badge badge-danger">Canceled</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                        @endif
                    </p>
                    <p><strong>Total Price:</strong> {{ number_format($order->final_price, 0, ',', '.') }} đ</p>

                    <h4>Ordered Items:</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Product Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                @php
                                    $product = json_decode($item->product_info, true) ?? [];
                                @endphp
                                <tr>
                                    <td>{{ $product['name'] ?? 'Unknown' }}</td>
                                    <td>{{ $product['sku'] ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        {{ isset($product['price']) ? number_format(floatval($product['price']) * $item->quantity, 0, ',', '.') . ' đ' : 'N/A' }}
                                    </td>
                                    <td>
                                        @if (!empty($product['thumbnail']))
                                            <img src="{{ asset('storage/' . $product['thumbnail']) }}" width="50"
                                                height="50" alt="Product Image">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

</main>
