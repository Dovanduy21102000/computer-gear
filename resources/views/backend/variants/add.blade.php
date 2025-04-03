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
                        <h5 class="card-title">Thông tin biến thể</h5>
                        <form
                            action="{{ route('variants.store', ['product' => $product->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <div id="variants">
                                <div class="variant mb-4">
                                    <div class="row mb-3">
                                        <label for="variants[0][sku]" class="col-sm-2 col-form-label">SKU Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="variants[0][sku]" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="variants[0][price]" class="col-sm-2 col-form-label">Giá Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="variants[0][price]"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="variants[0][quantity]" class="col-sm-2 col-form-label">Số lượng Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="variants[0][quantity]"
                                                required>
                                        </div>
                                    </div>
                                    {{-- <div class="row mb-3">
                                        <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc tính</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="variants[0][attributes][]" multiple required>
                                                @foreach ($attributes as $attribute)
                                                    <optgroup label="{{ $attribute->name }}">
                                                        @foreach ($attribute->attributeValues as $value)
                                                            <option value="{{ $value->id }}">{{ $value->value }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <button type="submitsubmit" class="btn btn-secondary">Thêm biến thể</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>
