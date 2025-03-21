<main id="content" role="main" class="cart-page">
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a
                                href="{{ route('home.index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="mb-4">
            <h1 class="text-center">Cart</h1>
        </div>
        <div class="mb-10 cart-table">
            <form action="{{ route('cart.update') }}" id="cart-form" method="post">
                @csrf
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" id="delete-selected"
                        class="btn btn-danger ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block mx-2 mb-2"
                        style="border-radius: 0%">
                        Delete Selected
                    </button>
                </div>
                <table class="table" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="select-all"></th>
                            <!-- Select All Checkbox -->
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name">Product</th>
                            <th class="product-price">Price</th>
                            <th class="product-quantity w-lg-15">Quantity</th>
                            <th class="product-subtotal">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                        class="select-item">
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('cart.remove', $item->id) }}"
                                        class="text-gray-32 font-size-26">×</a>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <a href="#">
                                        <img class="img-fluid max-width-100 p-1 border border-color-1"
                                            src="{{ asset('storage/' . $item->product->thumbnail ?? 'default-image.jpg') }}"
                                            alt="{{ $item->product->name }}">
                                    </a>
                                </td>
                                <td data-title="Product">
                                    <a href="#" class="text-gray-90">{{ $item->product->name }}</a>
                                </td>
                                <td data-title="Price">
                                    <span class="">{{ number_format($item->product->price, 0, ',', '.') }}₫</span>
                                </td>
                                <td data-title="Quantity">
                                    <div class="border rounded-pill py-1 width-122 w-xl-80 px-3 border-color-1">
                                        <div class="js-quantity row align-items-center">
                                            <div class="col">
                                                <input type="hidden" name="cart[{{ $item->id }}][id]"
                                                    value="{{ $item->id }}">
                                                <input
                                                    class="js-result form-control h-auto border-0 rounded p-0 shadow-none"
                                                    type="number" name="cart[{{ $item->id }}][quantity]"
                                                    value="{{ $item->quantity }}" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-title="Total">
                                    <span
                                        class="">{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}₫</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="8" class="border-top space-top-2 justify-content-center">
                                <div class="pt-md-3">
                                    <div class="d-block d-md-flex flex-center-between">
                                        <div class="mb-3 mb-md-0 w-xl-40"></div>


                                        <!-- Update Cart Button -->
                                        <div class="d-md-flex">

                                            <button type="submit"
                                                class="btn btn-soft-secondary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 w-100 w-md-auto">
                                                Update Cart
                                            </button>

                                            <!-- Checkout Button -->
                                            <a href="#"
                                                class="btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block">
                                                Proceed to Checkout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <div class="mb-3 mb-md-0 w-xl-40">
                <form action="{{ route('cart.applyCoupon') }}" method="POST">
                    @csrf
                    <label class="sr-only" for="coupon_code">Coupon code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="coupon_code" id="coupon_code"
                            placeholder="Enter coupon code">
                        <div class="input-group-append">
                            <button class="btn btn-block btn-dark px-4" type="submit">
                                <i class="fas fa-tags d-md-none"></i>
                                <span class="d-none d-md-inline">Apply coupon</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="mb-8 cart-total">
            <div class="row">
                <div class="col-xl-5 col-lg-6 offset-lg-6 offset-xl-7 col-md-8 offset-md-4">
                    <div class="border-bottom border-color-1 mb-3">
                        <h3 class="d-inline-block section-title mb-0 pb-2 font-size-26">Cart totals</h3>
                    </div>
                    <table class="table mb-3 mb-md-0">
                        <tbody>
                            @php
                                $subtotal = 0;
                                foreach ($cartItems as $item) {
                                    $subtotal += $item->product->price * $item->quantity;
                                }

                                $discount = session('coupon')['discount'] ?? 0;
                                $total = max(0, $subtotal - $discount); // Ensure total never goes negative
                            @endphp

                            <tr class="cart-subtotal">
                                <th>Subtotal</th>
                                <td data-title="Subtotal">
                                    <span class="amount">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                                </td>
                            </tr>

                            @if (session('coupon'))
                                <tr class="coupon-discount">
                                    <th>Coupon ({{ session('coupon')['code'] }})</th>
                                    <td data-title="Discount">
                                        <span class="text-danger">- {{ number_format($discount, 0, ',', '.') }}₫<<
                                                /span>
                                    </td>
                                </tr>
                            @endif

                            <tr class="order-total">
                                <th>Total</th>
                                <td data-title="Total">
                                    <strong><span
                                            class="amount">{{ number_format($total, 0, ',', '.') }}₫</span></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="#"
                        class="btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-md-none">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('select-all').addEventListener('click', function(event) {
                document.querySelectorAll('.select-item').forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                });
            });

            document.getElementById('delete-selected').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission
                let selectedItems = document.querySelectorAll('.select-item:checked');
                if (selectedItems.length === 0) {
                    alert('Please select at least one item to delete.');
                    return;
                }
                if (confirm('Are you sure you want to delete the selected items?')) {
                    let form = document.getElementById('cart-form');
                    if (form) {
                        form.action = "{{ route('cart.bulkDelete') }}";
                        form.submit();
                    } else {
                        console.error("Form not found!");
                    }
                }
            });

            document.getElementById('update-cart').addEventListener('click', function(event) {
                event.preventDefault();
                let form = document.getElementById('cart-form');
                if (form) {
                    form.action = "{{ route('cart.update') }}"; // Ensure update action
                    form.submit();
                } else {
                    console.error("Form not found!");
                }
            });
        });
    </script>
</main>
