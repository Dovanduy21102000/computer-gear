<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin sản phẩm</h5>

                        <!-- Hiển thị thông tin sản phẩm -->
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Ảnh đại diện -->
                                @if ($product->thumbnail)
                                    <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid" alt="Ảnh sản phẩm">
                                @else
                                    <p>Không có ảnh</p>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <!-- Thông tin chi tiết -->
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>Tên sản phẩm:</strong> {{ $product->name }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'Không có' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>SKU:</strong> {{ $product->sku }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Giá khuyến mãi:</strong>
                                        @if ($product->price_sale)
                                            {{ number_format($product->price_sale, 0, ',', '.') }} VNĐ
                                        @else
                                            Không có
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Số lượng:</strong> {{ $product->quantity }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Trạng thái:</strong>
                                        @if ($product->status == 1)
                                            <span class="badge bg-success">Kích hoạt</span>
                                        @else
                                            <span class="badge bg-danger">Vô hiệu hóa</span>
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Có biến thể:</strong>
                                        @if ($product->is_variant == 1)
                                            <span class="badge bg-primary">Có</span>
                                        @else
                                            <span class="badge bg-secondary">Không</span>
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Mô tả ngắn:</strong> {{ $product->short_description }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Mô tả chi tiết:</strong> {{ $product->description }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Nút quay lại -->
                        <div class="mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>