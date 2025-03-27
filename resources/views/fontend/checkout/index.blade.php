<main id="content" role="main" class="checkout-page">
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
        <div class="mb-5">
            <h1 class="text-center">Checkout</h1>
        </div>

        <!-- Accordion -->
        <div id="shopCartAccordion1" class="accordion rounded mb-6">
            <!-- Card -->
            <div class="card border-0">
                <div id="shopCartHeadingTwo" class="alert alert-primary mb-0" role="alert">
                    Have a coupon? <a href="#" class="alert-link" data-toggle="collapse"
                        data-target="#shopCartTwo" aria-expanded="false" aria-controls="shopCartTwo">Click here to enter
                        your code</a>
                </div>
                <div id="shopCartTwo" class="collapse border border-top-0" aria-labelledby="shopCartHeadingTwo"
                    data-parent="#shopCartAccordion1" style="">
                    <form class="js-validate p-5" novalidate="novalidate">
                        <p class="w-100 text-gray-90">If you have a coupon code, please apply it below.</p>
                        <div class="input-group input-group-pill max-width-660-xl">
                            <input type="text" class="form-control" name="name" placeholder="Coupon code"
                                aria-label="Promo code">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-block btn-dark font-weight-normal btn-pill px-4">
                                    <i class="fas fa-tags d-md-none"></i>
                                    <span class="d-none d-md-inline">Apply coupon</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Accordion -->
        <form id="checkout-form" method="POST" class="js-validate">
            @csrf

            <div class="row">
                <!-- Order Summary -->
                <div class="col-lg-5 order-lg-2 mb-7 mb-lg-0">
                    <div class="pl-lg-3">
                        <div class="bg-gray-1 rounded-lg">
                            <div class="p-4 mb-4 checkout-table">
                                <div class="border-bottom border-color-1 mb-5">
                                    <h3 class="section-title mb-0 pb-2 font-size-25">Your order</h3>
                                </div>

                                <!-- Product Content -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">Product</th>
                                            <th class="product-total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            <tr class="cart_item">
                                                <td>
                                                    {{ $item->product->name }}
                                                    @if ($item->productVariant)
                                                        ({{ $item->productVariant->name }})
                                                    @endif
                                                    <strong class="product-quantity">× {{ $item->quantity }}</strong>
                                                </td>
                                                <td>
                                                    {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @php
                                            $subtotal = $cartItems->sum(
                                                fn($item) => $item->quantity * $item->product->price,
                                            );
                                            $discount = $appliedCoupon['discount'] ?? 0;
                                            $total = max(0, $subtotal - $discount);
                                        @endphp
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                        </tr>

                                        @if ($appliedCoupon)
                                            <tr>
                                                <th>Coupon ({{ $appliedCoupon['code'] }})</th>
                                                <td class="text-danger">-{{ number_format($discount, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                            <input type="hidden" name="coupon_code"
                                                value="{{ $appliedCoupon['code'] }}">
                                            <input type="hidden" name="coupon_discount" value="{{ $discount }}">
                                        @endif

                                        <tr>
                                            <th>Total</th>
                                            <td><strong>{{ number_format($total, 0, ',', '.') }}₫</strong></td>
                                            <input type="hidden" name="total_price" value="{{ (int) $total }}">
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Payment Methods -->
                                <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                    <div id="basicsAccordion1">
                                        {{-- <div class="border-bottom border-color-1 border-dotted-bottom">
                                            <div class="p-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="vnpay"
                                                        name="payment_method" value="vn_pay" checked>
                                                    <label class="custom-control-label form-label" for="vnpay">Thanh
                                                        toán qua VNPay</label>
                                                </div>
                                            </div>
                                        </div> --}}

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
                                    class="btn btn-primary-dark-w btn-block btn-pill font-size-20 mb-3 py-3">Place
                                    order</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Details -->
                <div class="col-lg-7 order-lg-1">
                    <div class="pb-7 mb-7">
                        <div class="border-bottom border-color-1 mb-5">
                            <h3 class="section-title mb-0 pb-2 font-size-25">Billing details</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="fullName"
                                        value="{{ old('fullName', $user->name ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_address" required>
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
                                        value="{{ old('email', $user->email ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_phone"
                                        value="{{ old('phone', $user->phone ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="js-form-message mb-6">
                                    <label class="form-label">Order notes (optional)</label>
                                    <textarea class="form-control" name="notes" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let checkoutForm = document.getElementById("checkout-form");
                checkoutForm.addEventListener("submit", function(event) {
                    event.preventDefault();
                    let paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

                    if (paymentMethod === "momo") {
                        checkoutForm.action = "{{ route('momo.create') }}";
                    } else if (paymentMethod === "vn_pay") {
                        checkoutForm.action = "{{ route('vnpay.create') }}";
                    } else {
                        checkoutForm.action = "{{ route('checkout.process') }}";
                    }

                    checkoutForm.submit();
                });
            });
        </script>
    </div>
</main>
