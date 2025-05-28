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
                        <div class="row align-items-start">
                            <div class="col-md-4 text-center">
                                <!-- Ảnh đại diện -->
                                @if ($product->thumbnail)
                                    <img src="{{ Storage::url($product->thumbnail) }}"
                                        class="img-fluid rounded shadow mb-3"
                                        style="max-width: 250px; border-radius: 16px;" alt="Ảnh sản phẩm">
                                @else
                                    <div class="bg-light border rounded p-5 mb-3">Không có ảnh</div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <!-- Thông tin chi tiết -->
                                <div class="row g-2 mb-2">
                                    <div class="col-2 text-end fw-bold text-primary">Tên sản phẩm:</div>
                                    <div class="col-10">{{ $product->name }}</div>
                                    <div class="col-2 text-end fw-bold text-primary">Danh mục:</div>
                                    <div class="col-10">{{ $product->category->name ?? 'Không có' }}</div>
                                    <div class="col-2 text-end fw-bold text-primary">Thương hiệu:</div>
                                    <div class="col-10">{{ $product->brand->name ?? 'Không có' }}</div>
                                    <div class="col-2 text-end fw-bold text-primary">SKU:</div>
                                    <div class="col-10">{{ $product->sku }}</div>
                                    <div class="col-2 text-end fw-bold text-primary">Giá:</div>
                                    <div class="col-10"><span
                                            class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }}
                                            VNĐ</span></div>
                                    <div class="col-2 text-end fw-bold text-primary">Giá khuyến mãi:</div>
                                    <div class="col-10">
                                        @if ($product->price_sale)
                                            <span
                                                class="text-success fw-bold">{{ number_format($product->price_sale, 0, ',', '.') }}
                                                VNĐ</span>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </div>
                                    <div class="col-2 text-end fw-bold text-primary">Số lượng:</div>
                                    <div class="col-10">{{ $product->quantity }}</div>
                                    <div class="col-2 text-end fw-bold text-primary">Trạng thái:</div>
                                    <div class="col-10">
                                        @if ($product->status == 1)
                                            <span class="badge bg-success">Kích hoạt</span>
                                        @else
                                            <span class="badge bg-danger">Vô hiệu hóa</span>
                                        @endif
                                    </div>
                                    <div class="col-2 text-end fw-bold text-primary">Có biến thể:</div>
                                    <div class="col-10">
                                        @if ($product->is_variant == 1)
                                            <span class="badge bg-primary">Có</span>
                                        @else
                                            <span class="badge bg-secondary">Không</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <div class="border rounded p-3 bg-light h-100">
                                            <div class="fw-bold mb-1 text-primary"><i
                                                    class="bi bi-info-circle me-1"></i> Mô tả ngắn:</div>
                                            <div class="admin-product-short-description-scrollable"
                                                style="max-height: 200px; overflow-y: auto;">
                                                {!! $product->short_description !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="border rounded p-3 bg-light h-100">
                                            <div class="fw-bold mb-1 text-primary"><i class="bi bi-card-text me-1"></i>
                                                Mô tả chi tiết:</div>
                                            <div class="admin-product-description-scrollable"
                                                style="max-height: 300px; overflow-y: auto;">
                                                {!! $product->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($product->is_variant && $product->variants && $product->variants->count())
                            <div class="border rounded p-3 mb-2 bg-white mt-3">
                                <div class="fw-bold mb-2 text-primary"><i class="bi bi-layers me-1"></i> Danh sách biến
                                    thể:</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>SKU</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $variant)
                                                <tr>
                                                    <td>{{ $variant->sku }}</td>
                                                    <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                                    <td>{{ $variant->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        

                        @if (!empty($albumImages))
                            <div class="mt-4">
                                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-images me-1"></i> Album ảnh sản
                                    phẩm</h5>
                                <div class="row g-3">
                                    @foreach ($albumImages as $img)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="border rounded shadow-sm p-1 bg-white h-100 text-center">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Ảnh album"
                                                    class="img-fluid rounded"
                                                    style="max-height:120px;object-fit:cover;cursor:pointer"
                                                    onclick="window.open(this.src, '_blank')">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Nút quay lại -->
                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary" title="Quay lại"><i
                                class="bi bi-arrow-left"></i> Quay lại</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm"
                            title="Sửa sản phẩm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa?')" title="Xóa sản phẩm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .admin-product-description-scrollable img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto 1rem auto;
    }

    .admin-product-short-description-scrollable img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto 1rem auto;
    }

    .admin-product-short-description-scrollable ul,
    .admin-product-short-description-scrollable ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
