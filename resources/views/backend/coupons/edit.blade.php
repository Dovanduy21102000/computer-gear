<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chỉnh sửa mã khuyến mại</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cập nhật thông tin mã khuyến mại</h5>

                        <!-- Hiển thị lỗi nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên khuyến mại</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{ $coupon->name }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="code" class="col-sm-2 col-form-label">Mã giảm giá</label>
                                <div class="col-sm-10">
                                    <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="type" class="col-sm-2 col-form-label">Loại giảm giá</label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-select">
                                        <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                        <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Số tiền giảm</label>
                                <div class="col-sm-10">
                                    <input type="number" name="price" class="form-control" value="{{ $coupon->price }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="maximum_amount" class="col-sm-2 col-form-label">Giá trị tối đa</label>
                                <div class="col-sm-10">
                                    <input type="number" name="maximum_amount" class="form-control" value="{{ $coupon->maximum_amount }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="min_order_total" class="col-sm-2 col-form-label">Đơn hàng tối thiểu</label>
                                <div class="col-sm-10">
                                    <input type="number" name="min_order_total" class="form-control" value="{{ $coupon->min_order_total }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="max_uses" class="col-sm-2 col-form-label">Số lần sử dụng tối đa</label>
                                <div class="col-sm-10">
                                    <input type="number" name="max_uses" class="form-control" value="{{ $coupon->max_uses }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="expire_date" class="col-sm-2 col-form-label">Ngày hết hạn</label>
                                <div class="col-sm-10">
                                    <input type="date" name="expire_date" class="form-control" value="{{ $coupon->expire_date }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $coupon->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ $coupon->status == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Cập nhật mã khuyến mại</button>
                                </div>
                            </div>
                        </form><!-- End Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
