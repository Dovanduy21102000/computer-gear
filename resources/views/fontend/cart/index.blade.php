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
            <style>
                .table {
                    background-color: #FFF6DC;
                    /* Soft Golden Cream */
                    border: 1.2px solid #D9B867;
                    /* Balanced Gold Borders */
                    border-radius: 6px;
                }

                .table th {
                    background-color: #F8D472;
                    /* Warm Gold */
                    color: #3D3D3D;
                    /* Clear but Soft Dark Gray */
                    border-bottom: 1.2px solid #D9B867;
                    padding: 12px;
                }

                .table td {
                    border-top: 1px solid #D9B867;
                    /* Defined but Not Harsh */
                    color: #3D3D3D;
                    padding: 10px;
                }


                .btn-primary-dark-w {
                    background-color: #F8D472;
                    /* Rich Warm Gold */
                    color: #3D3D3D;
                    border: none;
                    border-radius: 5px;
                }
            </style>
            <div class="container">
                <div class="mb-4">
                    <h1 class="text-center">Giỏ hàng</h1>
                </div>
                <div class="mb-10 cart-table">
                    <form action="{{ route('cart.bulkDelete') }}" id="cart-form" method="POST">
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
                                            @if ($item->productVariant)
                                                <div class="variant-attributes">
                                                    @php
                                                        $groupedAttributes = [];
                                                        foreach ($item->productVariant->attributeValues as $value) {
                                                            if (isset($value->attribute)) {
                                                                $attrName = $value->attribute->name;
                                                                if (!isset($groupedAttributes[$attrName])) {
                                                                    $groupedAttributes[$attrName] = $value->value;
                                                                }
                                                            }
                                                        }
                                                        $formattedAttributes = [];
                                                        foreach ($groupedAttributes as $name => $value) {
                                                            $formattedAttributes[] = $name . ': ' . $value;
                                                        }
                                                    @endphp
                                                    <small class="text-dark">
                                                        {{ implode(' | ', $formattedAttributes) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td data-title="Price">
                                            @php
                                                $price = $item->productVariant
                                                    ? $item->productVariant->price_sale ?? $item->productVariant->price
                                                    : $item->product->price_sale ?? $item->product->price;

                                                $originalPrice = $item->productVariant
                                                    ? $item->productVariant->price
                                                    : $item->product->price;

                                                $salePrice = $item->productVariant
                                                    ? $item->productVariant->price_sale
                                                    : $item->product->price_sale;
                                            @endphp
                                            @if ($salePrice)
                                                <del
                                                    class="text-muted">{{ number_format($originalPrice, 0, ',', '.') }}₫</del>
                                                <span
                                                    class="text-danger">{{ number_format($salePrice, 0, ',', '.') }}₫</span>
                                            @else
                                                <span>{{ number_format($price, 0, ',', '.') }}₫</span>
                                            @endif
                                        </td>

                                        <td data-title="Quantity">
                                            <div class="border rounded-pill py-1 width-122 w-xl-80 px-3 border-color-1"
                                                style="background: white;">
                                                <div class="js-quantity row align-items-center">
                                                    <div class="col">
                                                        <input type="hidden" name="cart[{{ $item->id }}][id]"
                                                            value="{{ $item->id }}">
                                                        <input
                                                            class="js-result form-control h-auto border-0 rounded p-0 shadow-none"
                                                            type="number" name="cart[{{ $item->id }}][quantity]"
                                                            value="{{ $item->quantity }}" min="1"
                                                            max="{{ $item->productVariant ? $item->productVariant->quantity : $item->product->quantity }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td data-title="Total">
                                            <span
                                                class="">{{ number_format($item->quantity * $price, 0, ',', '.') }}₫</span>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="8" class="border-top space-top-2 justify-content-center">
                                        <div class="pt-md-3">
                                            <div class="d-block d-md-flex flex-center-between">
                                                <div class="mb-3 mb-md-0 w-xl-40"></div>

                                                <!-- Nút cập nhật giỏ hàng -->
                                                <div class="d-md-flex">
                                                    <button type="submit" id="update-cart"
                                                        class="btn btn-soft-secondary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 w-100 w-md-auto">
                                                        Cập nhật giỏ hàng
                                                    </button>

                                                    <!-- Nút thanh toán -->
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
                            alert('Vui lòng chọn ít nhất một mục để thanh toán ');
                            return;
                        }

                        // Get selected item IDs
                        let selectedIds = Array.from(selectedItems).map(item => item.value);

                        // Create URL with selected items
                        let checkoutUrl = "{{ route('checkout.index') }}?selected_items=" + selectedIds.join(',');

                        // Redirect to checkout page with selected items
                        window.location.href = checkoutUrl;
                    });

                    document.getElementById('delete-selected').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default form submission
                        let selectedItems = document.querySelectorAll('.select-item:checked');
                        if (selectedItems.length === 0) {
                            alert('Vui lòng chọn ít nhất một mục để xóa.');
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
