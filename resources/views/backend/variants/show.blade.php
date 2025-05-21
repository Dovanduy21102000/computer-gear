<main id="main" class="main">
  <div class="pagetitle">
      <h1>Chi tiết sản phẩm biến thể</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
              <li class="breadcrumb-item">
                <a href="{{ route('variants.index', $variant->product->id) }}">Danh sách sản phẩm biến thể của {{ $variant->product->name }}</a>
             </li>             
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
                          <div class="col-md-4 text-center">
                              <img src="{{ Storage::url($variant->image) }}" alt="Ảnh sản phẩm" class="img-fluid rounded shadow-sm">
                          </div>
                          <div class="col-md-8">
                              <h4 class="text-primary">{{ $variant->product->name }}</h4>
                              <p><strong>Danh mục:</strong> {{ $variant->product->category->name ?? 'Không có' }}</p>
                              <p><strong>Thương hiệu:</strong> {{ $variant->product->brand->name ?? 'Không có' }}</p>
                              <p><strong>Giá:</strong> {{ number_format($variant->price, 0, ',', '.') }} VNĐ</p>
                              <p><strong>Giá giảm giá:</strong> {{ number_format($variant->price_sale, 0, ',', '.') }} VNĐ</p>
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
                                  @forelse($variant->attributes as $attribute)
                                      <li><strong>{{ $attribute->attribute_name }}:</strong> {{ $attribute->attribute_value }}</li>
                                  @empty
                                      <li>Không có thông tin thuộc tính.</li>
                                  @endforelse
                              </ul>
                          </div>
                          <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Quay lại</a>
                            <a href="{{ route('variants.edit', ['product' => $variant->product->id, 'variant' => $variant->id]) }}" class="btn btn-warning">Chỉnh sửa</a>
                          </div>
                      </div><!-- End row -->
                  </div><!-- End card-body -->
              </div><!-- End card -->
          </div>
      </div>
  </section>
</main>



