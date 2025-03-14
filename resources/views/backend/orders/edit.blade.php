<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="pagetitle">
        <h1>Chỉnh sửa đơn hàng</h1>
        <nav>
            <ol class="breadcrumb">
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Chỉnh sửa đơn hàng</h5>

                        <!-- Form cập nhật đơn hàng -->
                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- ID Đơn Hàng -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $order->id }}" disabled>
                                </div>
                            </div>

                            <!-- Người mua -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">User</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $order->user->name ?? 'N/A' }}"
                                        disabled>
                                </div>
                            </div>

                            <!-- Thông tin giao hàng -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tên người nhận</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_user_name" class="form-control"
                                        value="{{ old('shipping_user_name', $order->shipping_user_name) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="shipping_email" class="form-control"
                                        value="{{ old('shipping_email', $order->shipping_email) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Số điện thoại</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_phone" class="form-control"
                                        value="{{ old('shipping_phone', $order->shipping_phone) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Địa chỉ</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_address" class="form-control"
                                        value="{{ old('shipping_address', $order->shipping_address) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tỉnh/Thành phố</label>
                                <div class="col-sm-10">
                                    <select name="province_id" id="province" class="form-control">
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        @foreach ($provinces as $province)
                                            {{-- {{ dd($province) }} --}}
                                            <option value="{{ $province['code'] }}"
                                                {{ old('province_id', $order->shipping_province) == $province['code'] ? 'selected' : '' }}>
                                                {{ $province['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Quận/Huyện</label>
                                <div class="col-sm-10">
                                    <select name="shipping_city" id="district" class="form-control">
                                        <option value="">Chọn quận/huyện</option>

                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Địa chỉ cụ thể</label>
                                <div class="col-sm-10">
                                    <input type="text" name="specific_address" class="form-control"
                                        value="{{ old('specific_address', $order->specific_address) }}">
                                </div>
                            </div>

                            <!-- Mã giảm giá -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Mã giảm giá</label>
                                <div class="col-sm-10">
                                    <input type="text" name="coupon_code" class="form-control"
                                        value="{{ old('coupon_code', $order->coupon_code) }}" disabled>
                                </div>
                            </div>

                            <!-- Tổng tiền -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tổng giá trị đơn hàng</label>
                                <div class="col-sm-10">
                                    <input type="text" name="total_price" class="form-control"
                                        value="{{ old('total_price', $order->total_price) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tổng tiền thanh toán</label>
                                <div class="col-sm-10">
                                    <input type="text" name="final_price" class="form-control"
                                        value="{{ old('final_price', $order->final_price) }}">
                                </div>
                            </div>

                            <!-- Trạng thái đơn hàng -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Trạng thái thanh toán</label>
                                <div class="col-sm-10">
                                    <select name="payment_status" class="form-select" disabled>
                                        <option value="pending" {{ $order->payment_status == '0' ? 'selected' : '' }}>
                                            Chờ thanh toán
                                        </option>
                                        <option value="paid" {{ $order->payment_status == '1' ? 'selected' : '' }}>
                                            Đã thanh toán
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Trạng thái đơn hàng</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                            Chờ xử lý</option>
                                        <option value="processing"
                                            {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="canceled"
                                            {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Đang giao hàng</option>
                                        <option value="completed"
                                            {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>
                                            Đã hủy</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Phương thức thanh toán</label>
                                <div class="col-sm-10">
                                    <select name="payment_method" class="form-select">
                                        <option value="cash"
                                            {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                        <option value="bank"
                                            {{ $order->payment_method == 'vn_pay' ? 'selected' : '' }}>VN Pay
                                        </option>
                                        <option value="bank"
                                            {{ $order->payment_method == 'momo' ? 'selected' : '' }}>Momo
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Ghi chú</label>
                                <div class="col-sm-10">
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $order->notes) }}</textarea>
                                </div>
                            </div>

                            <!-- Nút cập nhật -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Cập nhật đơn hàng</button>
                                </div>
                            </div>

                        </form><!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        // Lấy quận/huyện khi tỉnh/thành phố được chọn
        document.getElementById('province').addEventListener('change', function() {
            let provinceCode = this.value;
            if (provinceCode) {
                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=02`)
                    .then(response => response.json())
                    .then(data => {
                        let districtSelect = document.getElementById('district');
                        console.log('T', data)
                        districtSelect.innerHTML =
                            '<option value="">Chọn quận/huyện</option>'; // Clear previous options
                        data.districts.forEach(district => {
                            let option = document.createElement('option');
                            option.value = district.code;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false; // Enable district dropdown
                    });
            } else {
                document.getElementById('district').disabled = true;
            }
        });
    </script>
</main><!-- End #main -->
