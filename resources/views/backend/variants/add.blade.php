<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm biến thể sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm biến thể</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tạo biến thể cho sản phẩm: {{ $product->name }}</h5>
                        <form action="{{ route('variants.store', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- SKU biến thể -->
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU biến thể</label>
                                <div class="col-sm-10">
                                    <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}" required>
                                </div>
                            </div>

                            <!-- Giá biến thể -->
                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                                </div>
                            </div>

                            <!-- Giá khuyến mãi (nếu có) -->
                            <div class="row mb-3">
                                <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" name="price_sale" id="price_sale" class="form-control" value="{{ old('price_sale') }}">
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="row mb-3">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                                </div>
                            </div>

                            <!-- Ảnh biến thể -->
                            <div class="row mb-3">
                                <label for="thumbnail" class="col-sm-2 col-form-label">Ảnh biến thể</label>
                                <div class="col-sm-10">
                                    <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                                </div>
                            </div>

                            <!-- Danh sách thuộc tính với kiểu tự chọn -->
                            <h6>Chọn thuộc tính</h6>
                            @foreach ($attributes as $attribute)
                                <div class="row mb-3">
                                    <label for="attribute_{{ $attribute->id }}" class="col-sm-2 col-form-label">{{ $attribute->name }}</label>
                                    <div class="col-sm-10">
                                        <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-select" required>
                                            <option value="">Chọn {{ $attribute->name }}</option>
                                            @foreach ($attribute->attributeValues as $value)
                                                <option value="{{ $value->id }}" {{ old("attributes.{$attribute->id}") == $value->id ? 'selected' : '' }}>
                                                    {{ $value->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-end">
                                <button type="submit" class="btn btn-secondary">Lưu biến thể</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>




