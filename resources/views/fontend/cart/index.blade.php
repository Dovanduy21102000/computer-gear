        <main id="content" role="main" class="cart-page">
            <div class="container bg-md-transparent" style="padding: 10px; border-bottom: 1px solid #9d9c9c;">
                <div class="container">
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a
                                        href="{{ route('home.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">
                                    Giỏ hàng</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="mb-4">
                    <h1 class="text-center">Giỏ hàng</h1>
                </div>
                <div class="mb-10 cart-table">
                    <form action="" id="cart-form" method="GET">
                        @csrf
                        <div class="d-flex justify-content-end mb-4">
                            <button type="submit" id="delete-selected"
                                class="btn btn-block btn-dark ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block mx-2 mb-2"
                                style="border-radius: 0%">
                                Xoá những mục đã chọn
                            </button>
                        </div>
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="select-all"></th>
                                    <!-- Select All Checkbox -->
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Tên sản phẩm</th>
                                    <th class="product-price">Giá tiền</th>
                                    <th class="product-quantity w-lg-15">Số lượng</th>
                                    <th class="product-subtotal">Tổng tiền</th>
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
                                                <img class="img-fluid max-width-150 p-1 border border-color-1"
                                                    src="{{ asset('storage/' . $item->product->thumbnail ?? 'default-image.jpg') }}"
                                                    alt="{{ $item->product->name }}">
                                            </a>
                                        </td>
                                        <td data-title="Product">
                                            <a href="#" class="text-gray-90">{{ $item->product->name }}</a>
                                        </td>
                                        <td data-title="Price">
                                            @if ($item->product->price_sale)
                                                <del
                                                    class="text-muted">{{ number_format($item->product->price, 0, ',', '.') }}₫</del>
                                                <span
                                                    class="text-danger">{{ number_format($item->product->price_sale, 0, ',', '.') }}₫</span>
                                            @else
                                                <span>{{ number_format($item->product->price, 0, ',', '.') }}₫</span>
                                            @endif
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
                                                            value="{{ $item->quantity }}" min="1"
                                                            max="{{ $item->product->quantity }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-title="Total">
                                            @php
                                                $price = $item->product->price_sale ?? $item->product->price;
                                                $total = $item->quantity * $price;
                                            @endphp
                                            <span class="">{{ number_format($total, 0, ',', '.') }}₫</span>
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

                                                    <button type="submit" id="update-cart"
                                                        class="btn btn-soft-secondary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 w-100 w-md-auto">
                                                        Cập nhật giỏ hàng
                                                    </button>

                                                    <!-- Checkout Button -->
                                                    <button type="submit" id="checkout-selected"
                                                        class="btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block">
                                                        Tiến hành thanh toán
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>

            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById('select-all').addEventListener('click', function(event) {
                        document.querySelectorAll('.select-item').forEach(checkbox => {
                            checkbox.checked = event.target.checked;
                        });
                    });

                    document.getElementById('checkout-selected').addEventListener('click', function(event) {
                        event.preventDefault();

                        let selectedItems = document.querySelectorAll('.select-item:checked');
                        if (selectedItems.length === 0) {
                            alert('Please select at least one item to checkout.');
                            return;
                        }

                        let form = document.getElementById('cart-form');
                        if (form) {
                            form.action = "{{ route('checkout.index') }}"; // Set action to checkout route
                            form.submit();
                        } else {
                            console.error("Form not found!");
                        }
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
                            form.method = "POST";
                            form.action = "{{ route('cart.update') }}"; // Ensure update action
                            form.submit();
                        } else {
                            console.error("Form not found!");
                        }
                    });

                });
            </script>
        </main>
