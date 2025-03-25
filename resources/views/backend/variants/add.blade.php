<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm biến thể sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm biến thể</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin biến thể</h5>
                        <form action="{{ route('variants.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Sản phẩm cha -->
                            <div class="row mb-3">
                                <label for="product_id" class="col-sm-2 col-form-label">Sản phẩm</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="product_id" name="product_id" required>
                                        <option value="">Chọn sản phẩm</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Màu sắc -->
                            <div class="row mb-3">
                                <label for="color" class="col-sm-2 col-form-label">Màu sắc</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="color" name="color" required>
                                </div>
                            </div>

                            <!-- Kích thước -->
                            <div class="row mb-3">
                                <label for="size" class="col-sm-2 col-form-label">Kích thước</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="size" name="size">
                                </div>
                            </div>

                            <!-- Giá -->
                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" required>
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="row mb-3">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1">Kích hoạt</option>
                                        <option value="0">Vô hiệu hóa</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Ảnh biến thể -->
                            <div class="row mb-3">
                                <label for="image" class="col-sm-2 col-form-label">Ảnh biến thể</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>

                            <!-- Nút Submit -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Thêm biến thể</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>