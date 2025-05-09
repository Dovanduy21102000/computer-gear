<main id="main" class="main">
    <div class="pagetitle">
        <h1>Danh sách sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý sản phẩm biến thể</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Quản lý sản phẩm biến thể của {{ $product->name }}</h5>
                        
                        <!-- Thanh công cụ -->
                        <div class="d-flex justify-content-between mb-3">
                            <a class="btn btn-success" href="{{ route('variants.create', ['product' => $product->id]) }}">
                                Thêm mới
                            </a>
                            <input class="form-control w-25" placeholder="Tìm kiếm..." type="search" name="search">
                        </div>
                        
                        <!-- Bảng dữ liệu -->
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Ảnh đại diện của biến thể</th>
                                        <th class="text-center">Danh mục</th>
                                        <th class="text-center">Thương hiệu</th>
                                        <th class="text-center">Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variants as $variant)
                                        <tr>
                                            <td class="text-center">
                                                @if ($variant->image)
                                                    <img src="{{ Storage::url($variant->image) }}" 
                                                         width="50" height="50" style="border-radius: 5px;" 
                                                         alt="Ảnh sản phẩm">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $variant->product->category->name ?? 'Không có' }}</td>
                                            <td>{{ $variant->product->brand->name ?? 'Không có' }}</td>
                                            <td class="text-end">{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                            <td class="text-center">{{ $variant->quantity }}</td>
                                            <td class="text-center">
                                                @if ($variant->status == 1)
                                                    <span class="badge bg-success">Kích hoạt</span>
                                                @else
                                                    <span class="badge bg-danger">Vô hiệu hóa</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('variants.show', ['product' => $product->id,'variant'=>$variant->id]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                   <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('variants.edit', ['product' => $product->id,'variant'=>$variant->id]) }}" 
                                                   class="btn btn-outline-warning btn-sm">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('variants.destroy', ['product' => $product->id,'variant'=>$variant->id]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
