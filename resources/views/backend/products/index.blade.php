<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
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
                                    <input class="datatable-input" placeholder="Tìm kiếm theo tên hoặc SKU..." type="search" name="search" title="Tìm kiếm trong bảng">
                                </div>
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
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($product->thumbnail)
                                                        <img src="{{ Storage::url($product->thumbnail) }}" width="50" height="50" alt="Ảnh sản phẩm">
                                                    @else
                                                        <span>Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? 'Không có' }}</td>
                                                <td>{{ $product->brand->name ?? 'Không có' }}</td>
                                                <td class="text-end">{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                                                <td class="text-center">{{ $product->quantity }}</td>
                                                <td class="text-center">
                                                    @if ($product->status == 1)
                                                        <span class="badge bg-success">Kích hoạt</span>
                                                    @else
                                                        <span class="badge bg-danger">Vô hiệu hóa</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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