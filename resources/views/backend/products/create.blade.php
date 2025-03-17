<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm mới sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin sản phẩm</h5>

                        <!-- Form Thêm Mới Sản Phẩm -->
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Danh mục -->
                            <div class="row mb-3">
                                <label for="category_id" class="col-sm-2 col-form-label">Danh mục</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Thương hiệu -->
                            <div class="row mb-3">
                                <label for="brand_id" class="col-sm-2 col-form-label">Thương hiệu</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Chọn thương hiệu</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- SKU -->
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sku" name="sku" required>
                                </div>
                            </div>

                            <!-- Tên sản phẩm -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="slug" name="slug" required>
                                </div>
                            </div>

                            <!-- Ảnh đại diện -->
                            <div class="row mb-3">
                                <label for="thumbnail" class="col-sm-2 col-form-label">Ảnh đại diện</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                </div>
                            </div>

                            <!-- Mô tả ngắn -->
                            <div class="row mb-3">
                                <label for="short_description" class="col-sm-2 col-form-label">Mô tả ngắn</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="short_description" name="short_description" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Mô tả chi tiết -->
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Mô tả chi tiết</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                                </div>
                            </div>

                            <!-- Giá -->
                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" required>
                                </div>
                            </div>

                            <!-- Giá khuyến mãi -->
                            <div class="row mb-3">
                                <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price_sale" name="price_sale">
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="row mb-3">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        required>
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

                            <!-- Có biến thể -->
                            <div class="row mb-3">
                                <label for="is_variant" class="col-sm-2 col-form-label">Có biến thể</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="is_variant" name="is_variant" required>
                                        <option value="1">Có</option>
                                        <option value="0">Không</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Nút Submit -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                                </div>
                            </div>
                        </form><!-- End Form Thêm Mới Sản Phẩm -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
