<main id="main" class="main">
    <div class="pagetitle">
        <h1>Danh sách sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý sản phẩm</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách sản phẩm</h5>
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-top">
                                <div>
                                    <a class="btn btn-primary" href="{{ route('products.create') }}">Thêm mới</a>
                                </div>
                                <div class="datatable-search">
                                    <input class="datatable-input" placeholder="Tìm kiếm theo tên hoặc SKU..."
                                        type="search" name="search" title="Tìm kiếm trong bảng">
                                </div>
                                <form method="GET" action="{{ route('products.index') }}" class="mb-1">
                                    <div class="row">
                                        <!-- Lọc danh mục -->
                                        <div class="col-md-4">
                                            <select name="category" id="category" class="form-control">
                                                <option value="">-- Danh mục --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Lọc thương hiệu -->
                                        <div class="col-md-4">
                                            <select name="brand" id="brand" class="form-control">
                                                <option value="">-- Thương hiệu --</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Nút lọc -->
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('products.index') }}"
                                                class="btn btn-secondary ms-2">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="datatable-container">
                                <table class="table datatable datatable-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Ảnh đại diện</th>
                                            <th class="text-center">Tên sản phẩm</th>
                                            <th class="text-center">Danh mục</th>
                                            <th class="text-center">Thương hiệu</th>
                                            <th class="text-center">Giá</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Biến thể</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($product->thumbnail)
                                                        <img src="{{ Storage::url($product->thumbnail) }}"
                                                            width="50" height="50" alt="Ảnh sản phẩm">
                                                    @else
                                                        <span>Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? 'Không có' }}</td>
                                                <td>{{ $product->brand->name ?? 'Không có' }}</td>
                                                <td class="text-end">{{ number_format($product->price, 0, ',', '.') }}
                                                    VNĐ</td>
                                                <td class="text-center">{{ $product->quantity }}</td>
                                                <td class="text-center">
                                                    @if ($product->is_variant)
                                                        <span class="badge bg-info">{{ $product->variants->count() }}
                                                            biến thể</span>
                                                        <a href="{{ route('variants.index', $product->id) }}"
                                                            class="btn btn-info btn-sm mt-1">
                                                            <i class="fa fa-list"></i>
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary">Không có biến thể</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($product->status == 1)
                                                        <span class="badge bg-success">Kích hoạt</span>
                                                    @else
                                                        <span class="badge bg-danger">Vô hiệu hóa</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('products.show', $product->id) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
