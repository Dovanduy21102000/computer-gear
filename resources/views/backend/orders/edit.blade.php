<main id="main" class="main">
    @if (session()->has('success') && session()->get('success') == true)
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                                <label class="col-sm-2 col-form-label">Tên người mua</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $order->user->name ?? 'N/A' }}"
                                        disabled>
                                </div>
                            </div>

                            <!-- Thông tin giao hàng -->
                            @php
                                $isEditable = in_array($order->status, ['pending', 'processing']);
                            @endphp

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tên người nhận</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_user_name" class="form-control"
                                        value="{{ old('shipping_user_name', $order->shipping_user_name) }}"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="shipping_email" class="form-control"
                                        value="{{ old('shipping_email', $order->shipping_email) }}"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Số điện thoại</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_phone" class="form-control"
                                        value="{{ old('shipping_phone', $order->shipping_phone) }}"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Địa chỉ</label>
                                <div class="col-sm-10">
                                    <input type="text" name="shipping_address" class="form-control"
                                        value="{{ old('shipping_address', $order->shipping_address) }}"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provinceSelect" class="col-sm-2 col-form-label">Tỉnh/Thành phố</label>
                                <div class="col-sm-10">
                                    <select name="province_id" id="provinceSelect" class="form-select"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province['code'] ?? '' }}"
                                                {{ old('province_id', $order->province_id) == ($province['code'] ?? '') ? 'selected' : '' }}>
                                                {{ $province['name'] ?? 'Không xác định' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="districtSelect" class="col-sm-2 col-form-label">Quận/Huyện</label>
                                <div class="col-sm-10">
                                    <select name="district_id" id="districtSelect" class="form-select"
                                        {{ $isEditable ? '' : 'disabled' }}>
                                        <option value="">Chọn quận/huyện</option>
                                        @foreach ($districts as $district)
                                            @if (isset($district['code']) && isset($district['name']))
                                                <option value="{{ $district['code'] }}"
                                                    {{ old('district_id', $order->district_id) == $district['code'] ? 'selected' : '' }}>
                                                    {{ $district['name'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
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
                                        value="{{ old('total_price', $order->total_price) }}"disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tổng tiền thanh toán</label>
                                <div class="col-sm-10">
                                    <input type="text" name="final_price" class="form-control"
                                        value="{{ old('final_price', $order->final_price) }}"disabled>
                                </div>
                            </div>

                            <!-- Trạng thái đơn hàng -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Trạng thái thanh toán</label>
                                <div class="col-sm-10">
                                    <select name="payment_status" class="form-select" disabled>
                                        <option value="0" {{ $order->payment_status == '0' ? 'selected' : '' }}>
                                            Chờ thanh toán
                                        </option>
                                        <option value="1" {{ $order->payment_status == '1' ? 'selected' : '' }}>
                                            Đã thanh toán
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Trạng thái đơn hàng</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select"
                                        {{ $order->status === 'success' ? 'disabled' : '' }}>
                                        @if ($order->status === 'pending')
                                            <option value="pending" selected>Chờ xử lý</option>
                                            <option value="processing">Đang xử lý</option>
                                            <option value="canceled">Hủy đơn</option>
                                        @elseif ($order->status === 'processing')
                                            <option value="processing" selected>Đang xử lý</option>
                                            <option value="delivered">Đang giao hàng</option>
                                            <option value="canceled">Hủy đơn</option>
                                        @elseif ($order->status === 'delivered')
                                            <option value="delivered" selected>Đang giao hàng</option>
                                            <option value="completed">Đã giao hàng</option>
                                        @elseif ($order->status === 'completed')
                                            <option value="completed" selected>Đã giao hàng</option>
                                            <option value="success">Hoàn thành</option>
                                        @elseif ($order->status === 'success')
                                            <option value="success" selected>Hoàn thành</option>
                                        @elseif ($order->status === 'canceled')
                                            <option value="canceled" selected>Đã hủy</option>
                                            <option value="pending">Chờ xử lý</option>
                                            <option value="processing">Đang xử lý</option>
                                            <option value="delivered">Đang giao hàng</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- Phương thức thanh toán -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Phương thức thanh toán</label>
                                <div class="col-sm-10">
                                    <select name="payment_method" class="form-select" disabled>
                                        <option value="cash"
                                            {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                        <option value="vn_pay"
                                            {{ $order->payment_method == 'vn_pay' ? 'selected' : '' }}>VN Pay
                                        </option>
                                        <option value="momo"
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
                        <!-- Modal Confirm Cancel -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancelModalLabel">Xác nhận hủy đơn</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="cancelReason" class="form-label">Lý do hủy đơn:</label>
                                            <textarea id="cancelReason" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="button" class="btn btn-primary" id="confirmCancelBtn">Đồng ý
                                            hủy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#provinceSelect').change(function() {
                var provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: "{{ route('get.districts', '') }}/" + provinceId,
                        type: "GET",
                        success: function(data) {
                            $('#districtSelect').empty();
                            $('#districtSelect').append(
                                '<option value="">Chọn quận/huyện</option>');

                            if (data && data.length > 0) {
                                $.each(data, function(key, district) {
                                    $('#districtSelect').append('<option value="' +
                                        district.code + '">' + district.name +
                                        '</option>');
                                });
                            }

                            // Trigger the change event on districtSelect
                            $('#districtSelect').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching districts:", error);
                        }
                    });
                } else {
                    $('#districtSelect').empty();
                    $('#districtSelect').append('<option value="">Chọn quận/huyện</option>');
                }
            });
        });
    </script>

</main><!-- End #main -->
