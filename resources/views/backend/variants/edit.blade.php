<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('variants.index', $product->id) }}">Danh sách sản phẩm biến thể</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">Thông tin chi tiết sản phẩm biến thể</h5>

                        <div class="row">
                            <!-- Ảnh đại diện sản phẩm bên trái -->
                            <div class="col-md-4">
                                <div class="card border-light shadow-sm">
                                    <img src="{{ Storage::url($variant->product->thumbnail) }}" 
                                         alt="Ảnh sản phẩm" class="card-img-top" style="border-radius: 10px;">
                                </div>
                            </div>

                            <!-- Chi tiết sản phẩm biến thể bên phải -->
                            <div class="col-md-8 ms-4">
                                <div class="card border-light shadow-sm p-3 mb-4">
                                    <h4 class="text-primary">{{ $variant->product->name }}</h4>
                                    <p><strong>Danh mục:</strong> {{ $variant->product->category->name ?? 'Không có' }}</p>
                                    <p><strong>Thương hiệu:</strong> {{ $variant->product->brand->name ?? 'Không có' }}</p>
                                    <p><strong>Giá:</strong> {{ number_format($variant->price, 0, ',', '.') }} VNĐ</p>
                                    <p><strong>Số lượng:</strong> {{ $variant->quantity }}</p>
                                    <p><strong>Trạng thái:</strong> 
                                        @if ($variant->status == 1)
                                            <span class="badge bg-success">Kích hoạt</span>
                                        @else
                                            <span class="badge bg-danger">Vô hiệu hóa</span>
                                        @endif
                                    </p>

                                    <h5 class="mt-3">Thông tin thuộc tính:</h5>
                                    <ul class="list-unstyled">
                                        @if($variant->product->attributes && $variant->product->attributes->isNotEmpty())
                                            @foreach ($variant->product->attributes as $attribute)
                                                <li class="border-bottom py-2"><strong>{{ $attribute->name }}:</strong>
                                                    @foreach($attribute->values as $value)
                                                        <span class="badge bg-info">{{ $value->value }}</span>
                                                    @endforeach
                                                </li>
                                            @endforeach
                                        @else
                                            <li>Không có thông tin thuộc tính.</li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Các nút hành động -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('variants.index', $product->id) }}" class="btn btn-secondary">Quay lại</a>
                                    <a href="{{ route('variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}" class="btn btn-warning">Chỉnh sửa</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
