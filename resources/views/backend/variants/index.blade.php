<main id="main" class="main">
    <div class="pagetitle">
        <h1>Danh sách sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý sản phẩm biến thể</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <a class="btn btn-secondary" href="{{ route('products.index') }}">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
    </a>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Quản lý sản phẩm biến thể của {{ $product->name }}</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a class="btn btn-success me-2"
                                    href="{{ route('variants.create', ['product' => $product->id]) }}">
                                    Thêm mới
                                </a>
                            </div>
                            <input class="form-control w-25" placeholder="Tìm kiếm..." type="search" name="search">
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Ảnh đại diện của biến thể</th>
                                        <th class="text-center">SKU của biến thể</th>
                                        <th class="text-center">Thuộc tính</th>
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
                                                    <img src="{{ Storage::url($variant->image) }}" width="50"
                                                        height="50" style="border-radius: 5px;" alt="Ảnh sản phẩm">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $variant->sku }}</td>
                                            <td>
                                                @if ($variant->attributeValues && $variant->attributeValues->count())
                                                    @foreach ($variant->attributeValues as $attrValue)
                                                        <span>
                                                            @if (isset($attrValue->attribute))
                                                                {{ $attrValue->attribute->name }}:
                                                            @endif
                                                            {{ $attrValue->value }}
                                                        </span>
                                                        <br>
                                                    @endforeach
                                                @else
                                                    <span>Không có</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($variant->price, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="text-center">{{ $variant->quantity }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge status-toggle {{ $variant->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                                    data-id="{{ $variant->id }}" style="cursor:pointer">
                                                    {{ $variant->status == 1 ? 'Kích hoạt' : 'Vô hiệu hóa' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('variants.show', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                    class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form
                                                    action="{{ route('variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}"
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

                    </div><!-- End card-body -->
                </div><!-- End card -->
            </div>
        </div>
    </section>
</main>
