<main id="content" role="main" class="checkout-page">
    <style>
        .variant-attributes {
            margin-top: 5px;
        }

        .variant-attributes small {
            display: inline-block;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
            font-size: 0.85em;
            color: #6c757d;
        }

        .product-quantity {
            display: block;
            margin-top: 5px;
            color: #6c757d;
        }

        .coupon-list-container {
            scrollbar-width: thin;
            scrollbar-color: #D9B867 #FFF6DC;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 1rem;
        }

        .coupon-list-container::-webkit-scrollbar {
            width: 8px;
        }

        .coupon-list-container::-webkit-scrollbar-track {
            background: #FFF6DC;
        }

        .coupon-list-container::-webkit-scrollbar-thumb {
            background-color: #D9B867;
            border-radius: 4px;
        }

        .coupon-card {
            transition: all 0.3s ease;
        }

        .coupon-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .coupon-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .empty-coupon-state {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-coupon-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #D9B867;
        }

        .empty-coupon-state small {
            color: #bfa14a;
            font-size: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .empty-coupon-state small .fa-info-circle {
            font-size: 1.1em !important;
            margin-bottom: 0;
            margin-right: 6px;
            color: #bfa14a;
        }

        .coupon-icon {
            color: #D9B867;
            font-size: 1.2em;
            vertical-align: middle;
        }

        .checkout-navigation {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .checkout-navigation .btn {
            margin-right: 1rem;
        }

        .checkout-navigation .btn i {
            margin-right: 0.5rem;
        }
    </style>
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="../home/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Checkout
                        </li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <!-- Checkout Navigation -->
        {{-- <div class="checkout-navigation">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-info-circle"></i>
                        Đây là bước cuối cùng để xác nhận đơn hàng của bạn.
                        Nếu bạn muốn thay đổi giỏ hàng, vui lòng quay lại trang giỏ hàng.
                    </div>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                    <button type="button" class="btn btn-outline-secondary" id="refreshOrder">
                        <i class="fas fa-sync-alt"></i> Cập nhật đơn hàng
                    </button>
                </div>
            </div>
        </div> --}}

        <div class="mb-5">
            <h1 class="text-center">Thanh toán đơn hàng</h1>
        </div>

        <form action="{{ route('checkout.method') }}" id="checkout-form" method="POST" class="js-validate">
            @csrf

            <!-- Add hidden input for selected items -->
            @if (request()->has('selected_items'))
                @php
                    $selectedItems = explode(',', request()->input('selected_items'));
                @endphp
                @foreach ($selectedItems as $itemId)
                    <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                @endforeach
            @endif

            <div class="row">
                <!-- Order Summary -->
                <div class="col-lg-5 order-lg-2 mb-7 mb-lg-0">
                    <div class="pl-lg-3">
                        <div class="bg-gray-1 rounded-lg">
                            <div class="p-4 mb-4 checkout-table">
                                <div class="border-bottom border-color-1 mb-5">
                                    <h3 class="section-title mb-0 pb-2 font-size-25">Đơn hàng của bạn</h3>
                                </div>

                                <!-- Product Content -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">Sản phẩm</th>
                                            <th class="product-total">Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            <tr class="cart-item" data-item-id="{{ $item->id }}"
                                                data-quantity="{{ $item->quantity }}"
                                                data-price="{{ $item->productVariant ? $item->productVariant->price_sale ?? $item->productVariant->price : $item->product->price_sale ?? $item->product->price }}">
                                                <td>
                                                    <div>
                                                        <span style="display: inline;">
                                                            {{ $item->product->name }}
                                                            <strong style="display: inline; margin-left: 12px;">×
                                                                {{ $item->quantity }}</strong>
                                                        </span>
                                                    </div>
                                                    @if ($item->productVariant)
                                                        <div class="variant-attributes mt-1">
                                                            @php
                                                                $groupedAttributes = [];
                                                                foreach (
                                                                    $item->productVariant->attributeValues->sortBy(
                                                                        'attribute_id',
                                                                    )
                                                                    as $attributeValue
                                                                ) {
                                                                    if (isset($attributeValue->attribute)) {
                                                                        $attrName = $attributeValue->attribute->name;
                                                                        if (!isset($groupedAttributes[$attrName])) {
                                                                            $groupedAttributes[$attrName] =
                                                                                $attributeValue->value;
                                                                        }
                                                                    }
                                                                }
                                                                ksort($groupedAttributes); // Sort attributes by name
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
                                                <td>
                                                    @php
                                                        $price = $item->productVariant
                                                            ? $item->productVariant->price_sale ??
                                                                $item->productVariant->price
                                                            : $item->product->price_sale ?? $item->product->price;
                                                        $itemTotal = $item->quantity * $price;
                                                    @endphp
                                                    {{ number_format($itemTotal, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @php
                                            $subtotal = 0;
                                            foreach ($cartItems as $item) {
                                                $price = $item->productVariant
                                                    ? $item->productVariant->price_sale ?? $item->productVariant->price
                                                    : $item->product->price_sale ?? $item->product->price;
                                                $subtotal += $item->quantity * $price;
                                            }

                                            $appliedCoupon = session('coupon') ?? null;
                                            $discount = 0;

                                            if ($appliedCoupon) {
                                                if ($appliedCoupon['type'] === 'percent') {
                                                    $discount = min(
                                                        $subtotal * ($appliedCoupon['price'] / 100),
                                                        $appliedCoupon['maximum_amount'] ?? $subtotal,
                                                    );
                                                } else {
                                                    $discount = min($appliedCoupon['price'], $subtotal);
                                                }
                                            }

                                            $total = max(0, $subtotal - $discount);
                                        @endphp


                                        <tr>
                                            <th>Tổng cộng</th>
                                            <td class="text-right font-medium">
                                                {{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                        </tr>

                                        <tr>
                                            <th>Thành tiền</th>
                                            <td class="text-right font-medium">
                                                <strong>{{ number_format($total, 0, ',', '.') }}₫</strong>
                                            </td>
                                            <input type="hidden" name="total_price" value="{{ (int) $total }}">
                                        </tr>
                                        @if ($appliedCoupon)
                                            <tr>
                                                <td colspan="2" class="text-right text-danger"
                                                    style="font-size: 0.95em; border: none;">
                                                    Giảm giá: -{{ number_format($discount, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                        @endif
                                    </tfoot>



                                </table>

                                <!-- Coupon Section -->
                                <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">Mã giảm giá</h4>
                                        <button type="button" class="btn btn-link p-0" data-toggle="modal"
                                            data-target="#couponModal">
                                            <i class="fas fa-tags mr-1 coupon-icon"></i> Xem mã giảm giá
                                        </button>
                                    </div>

                                    <div id="couponDisplay" class="mb-3">
                                        @if ($appliedCoupon)
                                            <div class="card mb-3 shadow-sm border-left border-success"
                                                style="border-left-width: 6px !important;">
                                                <div
                                                    class="card-body d-flex align-items-center justify-content-between p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="font-weight-bold text-success mr-2">Đã áp dụng
                                                            mã:</span>
                                                        <span
                                                            class="font-weight-bold text-dark mr-2">{{ $appliedCoupon['code'] }}</span>
                                                        <span class="badge badge-success ml-2" style="font-size: 1em;">
                                                            @if ($appliedCoupon['type'] === 'percent')
                                                                {{ (int) $appliedCoupon['price'] }}%
                                                            @else
                                                                {{ number_format($appliedCoupon['price'], 0, ',', '.') }}₫
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-link text-danger p-0 remove-coupon"
                                                        title="Xóa mã giảm giá" style="font-size: 1.3em;">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Chọn mã giảm giá để nhận ưu đãi
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Methods -->
                                <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                    <div id="basicsAccordion1">
                                        <div class="border-bottom border-color-1 border-dotted-bottom">
                                            <div class="p-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="vnpay"
                                                        name="payment_method" value="vn_pay" checked>
                                                    <label class="custom-control-label form-label" for="vnpay">Thanh
                                                        toán qua VNPay</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border-bottom border-color-1 border-dotted-bottom">
                                            <div class="p-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="momo"
                                                        name="payment_method" value="momo">
                                                    <label class="custom-control-label form-label" for="momo">Thanh
                                                        toán qua MoMo</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border-bottom border-color-1 border-dotted-bottom">
                                            <div class="p-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="cash"
                                                        name="payment_method" value="cash">
                                                    <label class="custom-control-label form-label"
                                                        for="cash">Thanh
                                                        toán trực tiếp</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary-dark-w btn-block btn-pill font-size-20 mb-3 py-3">Đặt
                                    hàng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Details -->
                <div class="col-lg-7 order-lg-1">
                    <div class="pb-7 mb-7">
                        <div class="border-bottom border-color-1 mb-5">
                            <h3 class="section-title mb-0 pb-2 font-size-25">Chi tiết thanh toán</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_user_name"
                                        value="{{ old('shipping_user_name', Auth::user()->name ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_address"
                                        value="{{ old('shipping_address', '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Tỉnh thành <span class="text-danger">*</span></label>
                                    <select id="provinceSelect" name="province_id"
                                        class="form-control js-select selectpicker dropdown-select" required>
                                        <option value="">-- Chọn tỉnh thành --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="w-100"></div>

                            <!-- District Select -->
                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select id="districtSelect" name="district_id"
                                        class="form-control js-select selectpicker dropdown-select" required>
                                        <option value="">-- Chọn quận/huyện --</option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    let districtsByProvince = @json($districtsByProvince);

                                    document.getElementById("provinceSelect").addEventListener("change", function() {
                                        let provinceId = this.value;
                                        let districtSelect = document.getElementById("districtSelect");

                                        // Clear previous options
                                        districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';

                                        if (!provinceId || !districtsByProvince[provinceId] || districtsByProvince[provinceId]
                                            .length === 0) {
                                            // If no valid districts, reset and return
                                            console.log("No districts available for this province.");
                                            $('.selectpicker').selectpicker('refresh');
                                            return;
                                        }

                                        console.log("Selected Province ID:", provinceId);
                                        console.log("Available Districts:", districtsByProvince[provinceId]);

                                        // Populate districts
                                        districtsByProvince[provinceId].forEach(district => {
                                            let option = document.createElement("option");
                                            option.value = district.code;
                                            option.textContent = district.name;
                                            districtSelect.appendChild(option);
                                        });
                                        $('.selectpicker').selectpicker('refresh');
                                    });
                                });
                            </script>


                            <div class="col-md-6">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="shipping_email"
                                        value="{{ old('shipping_email', $user->email ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_phone"
                                        value="{{ old('shipping_phone', $user->phone ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Ghi chú đơn hàng (không bắt buộc)</label>
                                    <textarea class="form-control" name="notes" rows="4" value="{{ old('notes', '') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const userId = {{ Auth::id() }};

                // Subscribe to the checkout channel
                if (window.Echo) {
                    window.Echo.private(`checkout.${userId}`)
                        .listen('.CheckoutSessionUpdated', (e) => {
                            console.log('Received checkout update:', e);

                            // Update cart items
                            e.checkoutData.items.forEach(item => {
                                const itemElement = document.querySelector(`[data-item-id="${item.id}"]`);
                                if (itemElement) {
                                    // Update quantity display
                                    const quantityElement = itemElement.querySelector('strong');
                                    if (quantityElement) {
                                        quantityElement.textContent = `× ${item.quantity}`;
                                    }

                                    // Update price
                                    const priceElement = itemElement.querySelector('td:last-child');
                                    if (priceElement) {
                                        priceElement.textContent = formatPrice(item.price * item.quantity);
                                    }
                                }
                            });

                            // Update totals
                            const subtotalElement = document.querySelector(
                                'tr:has(th:contains("Tổng cộng")) td:last-child');
                            if (subtotalElement) {
                                subtotalElement.textContent = formatPrice(e.checkoutData.subtotal);
                            }

                            const totalElement = document.querySelector(
                                'tr:has(th:contains("Thành tiền")) td:last-child strong');
                            if (totalElement) {
                                totalElement.textContent = formatPrice(e.checkoutData.total);
                            }

                            // Update hidden total price input
                            const totalPriceInput = document.querySelector('input[name="total_price"]');
                            if (totalPriceInput) {
                                totalPriceInput.value = e.checkoutData.total;
                            }

                            // Update coupon display if exists
                            const couponDisplay = document.getElementById('couponDisplay');
                            if (couponDisplay) {
                                if (e.checkoutData.coupon) {
                                    const discountText = e.checkoutData.coupon.type === 'percent' ?
                                        `${parseInt(e.checkoutData.coupon.price)}%` :
                                        `${formatPrice(e.checkoutData.coupon.price)}`;

                                    couponDisplay.innerHTML = `
                                        <div class="card mb-3 shadow-sm border-left border-success" style="border-left-width: 6px !important;">
                                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                                    <span class="font-weight-bold text-success mr-2">Đã áp dụng mã:</span>
                                                    <span class="font-weight-bold text-dark mr-2">${e.checkoutData.coupon.code}</span>
                                                    <span class="badge badge-success ml-2" style="font-size: 1em;">
                                                        ${discountText}
                                                    </span>
                                                </div>
                                                <button type="button" class="btn btn-link text-danger p-0 remove-coupon" title="Xóa mã giảm giá" style="font-size: 1.3em;">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;

                                    // Reattach event listener to remove coupon button
                                    const removeButton = couponDisplay.querySelector('.remove-coupon');
                                    if (removeButton) {
                                        removeButton.addEventListener('click', function() {
                                            fetch('{{ route('remove-coupon') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector(
                                                            'meta[name="csrf-token"]').content
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        // Instead of reloading, update the UI
                                                        couponDisplay.innerHTML = `
                                                        <div class="text-muted">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Chọn mã giảm giá để nhận ưu đãi
                                                        </div>
                                                    `;
                                                    }
                                                });
                                        });
                                    }
                                } else {
                                    couponDisplay.innerHTML = `
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Chọn mã giảm giá để nhận ưu đãi
                                        </div>
                                    `;
                                }
                            }

                            // Show a subtle notification
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            toast.fire({
                                icon: 'info',
                                title: 'Giỏ hàng đã được cập nhật'
                            });
                        });
                }

                function formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(price);
                }

                // Handle refresh order button
                const refreshOrderBtn = document.getElementById('refreshOrder');
                if (refreshOrderBtn) {
                    refreshOrderBtn.addEventListener('click', function() {
                        const selectedItems = Array.from(document.querySelectorAll(
                                'input[name="selected_items[]"]'))
                            .map(input => input.value);

                        if (selectedItems.length > 0) {
                            window.location.href = "{{ route('checkout.index') }}?selected_items=" +
                                selectedItems.join(',');
                        } else {
                            window.location.href = "{{ route('checkout.index') }}";
                        }
                    });
                }

                // Handle coupon removal
                const removeCouponButton = document.querySelector('.remove-coupon');
                if (removeCouponButton) {
                    removeCouponButton.addEventListener('click', function() {
                        if (confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')) {
                            fetch('{{ route('remove-coupon') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .content
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.reload();
                                    }
                                });
                        }
                    });
                }

                // Simple form submission handler
                let checkoutForm = document.getElementById("checkout-form");
                checkoutForm.addEventListener("submit", function(event) {
                    event.preventDefault();
                    let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

                    if (!paymentMethod) {
                        alert("Vui lòng chọn phương thức thanh toán trước khi tiếp tục.");
                        return;
                    }

                    // Proceed with form submission
                    this.submit();
                });
            });
        </script>

    </div>

    <!-- Coupon Modal -->
    <div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="couponModalLabel">Mã giảm giá khả dụng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Manual Coupon Input -->
                    <div class="manual-coupon-section mb-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-tags mr-2 coupon-icon"></i>
                                    Bạn có mã giảm giá?
                                </h6>
                                <div class="input-group">
                                    <input type="text" id="manualCouponInput" class="form-control"
                                        placeholder="Nhập mã giảm giá của bạn...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="applyManualCoupon">
                                            Áp dụng
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Nhập mã giảm giá nếu bạn đã có
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="couponSearch" class="form-control"
                                        placeholder="Tìm kiếm mã giảm giá...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select id="couponFilter" class="form-control">
                                    <option value="all">Tất cả</option>
                                    <option value="percent">Giảm theo %</option>
                                    <option value="fixed">Giảm theo số tiền</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Coupons List with Fixed Height -->
                    <div class="coupon-list-container" style="height: 500px; overflow-y: auto;">
                        <div class="row" id="couponList">
                            <!-- Coupons will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let allCoupons = []; // Store all coupons for filtering

            // Load available coupons when modal opens
            $('#couponModal').on('show.bs.modal', function() {
                loadAvailableCoupons();
            });

            // Handle manual coupon input
            $('#applyManualCoupon').on('click', function() {
                const code = $('#manualCouponInput').val().trim();
                if (code) {
                    applyCoupon(code);
                } else {
                    alert('Vui lòng nhập mã giảm giá');
                }
            });

            // Handle Enter key in manual coupon input
            $('#manualCouponInput').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#applyManualCoupon').click();
                }
            });

            // Handle coupon removal
            $('.remove-coupon').on('click', function() {
                removeCoupon();
            });

            // Search functionality
            $('#couponSearch').on('input', function() {
                filterCoupons();
            });

            // Filter functionality
            $('#couponFilter').on('change', function() {
                filterCoupons();
            });

            function filterCoupons() {
                const searchTerm = $('#couponSearch').val().toLowerCase();
                const filterType = $('#couponFilter').val();

                const filteredCoupons = allCoupons.filter(coupon => {
                    const matchesSearch = coupon.code.toLowerCase().includes(searchTerm);
                    const matchesFilter = filterType === 'all' || coupon.type === filterType;
                    return matchesSearch && matchesFilter;
                });

                displayCoupons(filteredCoupons);
            }

            function displayCoupons(coupons) {
                const couponList = document.getElementById('couponList');
                couponList.innerHTML = '';

                if (coupons.length === 0) {
                    couponList.innerHTML = `
                        <div class="col-12">
                            <div class="empty-coupon-state">
                                <i class="fas fa-tags fa-3x mb-3"></i>
                                <h5 class="text-dark mb-2">Không có mã giảm giá khả dụng</h5>
                                <p class="text-muted mb-0">Hiện tại không có mã giảm giá nào phù hợp với đơn hàng của bạn</p>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Bạn có thể thử nhập mã giảm giá ở trên
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                    return;
                }

                coupons.forEach(coupon => {
                    const discountText = coupon.type === 'percent' ?
                        `${parseInt(coupon.price)}%` :
                        `${new Intl.NumberFormat('vi-VN').format(coupon.price)}₫`;

                    const minOrderText = coupon.min_order_total ?
                        `Đơn hàng tối thiểu ${new Intl.NumberFormat('vi-VN').format(coupon.min_order_total)}₫` :
                        'Không giới hạn giá trị đơn hàng';

                    let maxAmountText = '';
                    if (coupon.type === 'percent' && coupon.maximum_amount && coupon.maximum_amount > 0) {
                        maxAmountText =
                            ` <span class="text-muted ml-2">Giảm tối đa: ${new Intl.NumberFormat('vi-VN').format(coupon.maximum_amount)}₫</span>`;
                    }

                    const isDisabled = coupon.min_order_total > {{ $total }};

                    couponList.innerHTML += `
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 coupon-card ${isDisabled ? 'disabled' : ''}">
                                <div class="card-body">
                                    <h5 class="card-title">${coupon.code}</h5>
                                    <p class="card-text">
                                        <span class="badge badge-primary">Giảm ${discountText}</span>
                                        <small class="d-block text-muted mt-2">${minOrderText}${maxAmountText}</small>
                                    </p>
                                    <button class="btn btn-outline-primary btn-sm apply-coupon" 
                                            data-code="${coupon.code}"
                                            ${isDisabled ? 'disabled' : ''}>
                                        Áp dụng
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                // Add event listeners to apply buttons
                document.querySelectorAll('.apply-coupon').forEach(button => {
                    button.addEventListener('click', function() {
                        applyCoupon(this.dataset.code);
                    });
                });
            }

            function loadAvailableCoupons() {
                // Get the total from the hidden input (or fallback to 0)
                const total = document.querySelector('input[name="total_price"]')?.value || 0;
                fetch(`/cart/coupon/available?total=${total}`)
                    .then(response => response.json())
                    .then(data => {
                        allCoupons = data.coupons; // Store all coupons
                        displayCoupons(allCoupons); // Display all coupons initially
                    });
            }

            function applyCoupon(code) {
                fetch('{{ route('cart.applyCoupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            code: code
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#couponModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá');
                        }
                    });
            }

            function removeCoupon() {
                fetch('{{ route('remove-coupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
            }
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const userId = {{ Auth::id() }};

                // Subscribe to the checkout channel
                if (window.Echo) {
                    window.Echo.private(`checkout.${userId}`)
                        .listen('.CheckoutSessionUpdated', (e) => {
                            console.log('Received checkout update:', e);

                            // Update cart items
                            e.checkoutData.items.forEach(item => {
                                const itemElement = document.querySelector(`[data-item-id="${item.id}"]`);
                                if (itemElement) {
                                    // Update quantity display
                                    const quantityElement = itemElement.querySelector('strong');
                                    if (quantityElement) {
                                        quantityElement.textContent = `× ${item.quantity}`;
                                    }

                                    // Update price
                                    const priceElement = itemElement.querySelector('td:last-child');
                                    if (priceElement) {
                                        priceElement.textContent = formatPrice(item.price * item.quantity);
                                    }
                                }
                            });

                            // Update totals
                            const subtotalElement = document.querySelector(
                                'tr:has(th:contains("Tổng cộng")) td:last-child');
                            if (subtotalElement) {
                                subtotalElement.textContent = formatPrice(e.checkoutData.subtotal);
                            }

                            const totalElement = document.querySelector(
                                'tr:has(th:contains("Thành tiền")) td:last-child strong');
                            if (totalElement) {
                                totalElement.textContent = formatPrice(e.checkoutData.total);
                            }

                            // Update hidden total price input
                            const totalPriceInput = document.querySelector('input[name="total_price"]');
                            if (totalPriceInput) {
                                totalPriceInput.value = e.checkoutData.total;
                            }

                            // Update coupon display if exists
                            const couponDisplay = document.getElementById('couponDisplay');
                            if (couponDisplay) {
                                if (e.checkoutData.coupon) {
                                    const discountText = e.checkoutData.coupon.type === 'percent' ?
                                        `${parseInt(e.checkoutData.coupon.price)}%` :
                                        `${formatPrice(e.checkoutData.coupon.price)}`;

                                    couponDisplay.innerHTML = `
                                        <div class="card mb-3 shadow-sm border-left border-success" style="border-left-width: 6px !important;">
                                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                                    <span class="font-weight-bold text-success mr-2">Đã áp dụng mã:</span>
                                                    <span class="font-weight-bold text-dark mr-2">${e.checkoutData.coupon.code}</span>
                                                    <span class="badge badge-success ml-2" style="font-size: 1em;">
                                                        ${discountText}
                                                    </span>
                                                </div>
                                                <button type="button" class="btn btn-link text-danger p-0 remove-coupon" title="Xóa mã giảm giá" style="font-size: 1.3em;">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;

                                    // Reattach event listener to remove coupon button
                                    const removeButton = couponDisplay.querySelector('.remove-coupon');
                                    if (removeButton) {
                                        removeButton.addEventListener('click', function() {
                                            fetch('{{ route('remove-coupon') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector(
                                                            'meta[name="csrf-token"]').content
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        // Instead of reloading, update the UI
                                                        couponDisplay.innerHTML = `
                                                        <div class="text-muted">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Chọn mã giảm giá để nhận ưu đãi
                                                        </div>
                                                    `;
                                                    }
                                                });
                                        });
                                    }
                                } else {
                                    couponDisplay.innerHTML = `
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Chọn mã giảm giá để nhận ưu đãi
                                        </div>
                                    `;
                                }
                            }

                            // Show a subtle notification
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            toast.fire({
                                icon: 'info',
                                title: 'Giỏ hàng đã được cập nhật'
                            });
                        });
                }

                function formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(price);
                }

                // Simple form submission handler
                let checkoutForm = document.getElementById("checkout-form");
                checkoutForm.addEventListener("submit", function(event) {
                    event.preventDefault();
                    let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

                    if (!paymentMethod) {
                        alert("Vui lòng chọn phương thức thanh toán trước khi tiếp tục.");
                        return;
                    }

                    // Proceed with form submission
                    this.submit();
                });
            });
        </script>
    @endpush

</main>
