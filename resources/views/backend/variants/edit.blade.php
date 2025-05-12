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
            <form action="{{ route('variants.update', ['product' => $variant->product->id, 'variant' => $variant->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
                <div class="mb-3">
                    <label class="form-label">Mã SKU</label>
                    <input type="text" class="form-control" name="sku" value="{{ $variant->sku }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" class="form-control" name="price" value="{{ $variant->price }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $variant->quantity }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-control" name="status">
                        <option value="1" {{ $variant->status ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ !$variant->status ? 'selected' : '' }}>Vô hiệu hóa</option>
                    </select>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Ảnh sản phẩm:</label>
                    <div class="mb-2">
                        <img src="{{ Storage::url($variant->image) }}" alt="Ảnh sản phẩm" class="img-fluid rounded mb-3" style="max-width: 150px;">
                    </div>
                    <input type="file" class="form-control" name="image">
                </div>
            
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Hủy</a>
            </form>
            
      
        </section>
      
      
  </main>
  