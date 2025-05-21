<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm mới sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

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
                        <h5 class="card-title">Thông tin sản phẩm</h5>

                        <!-- Form Thêm Mới Sản Phẩm -->
                        <form action="{{ route('products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Danh mục -->
                            <div class="row mb-3">
                                <label for="category_id" class="col-sm-2 col-form-label">Danh mục</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Thương hiệu -->
                            <div class="row mb-3">
                                <label for="brand_id" class="col-sm-2 col-form-label">Thương hiệu</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Chọn thương hiệu</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <!-- SKU -->
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sku" name="sku" required
                                        value="{{ old('sku', $product->sku) }}">
                                </div>
                            </div>

                            <!-- Tên sản phẩm -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" required
                                        value="{{ old('namename', $product->name) }}">
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="{{ old('sku', $product->slug) }}">
                                </div>
                            </div>

                            <!-- Ảnh đại diện -->
                            <div class="row mb-3">
                                <label for="thumbnail" class="col-sm-2 col-form-label">Ảnh đại diện</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                    <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid"
                                        alt="Ảnh sản phẩm" width="50px">
                                </div>
                            </div>

                            <!-- Mô tả ngắn -->
                            <div class="row mb-3">
                                <label for="short_description" class="col-sm-2 col-form-label">Mô tả ngắn</label>
                                <div class="col-sm-10">
                                    <textarea id="ck_short_description" name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>
                            </div>

                            <!-- Mô tả chi tiết -->
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Mô tả chi tiết</label>
                                <div class="col-sm-10">
                                    <textarea id="ck_description" name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>


                            <script>
                                function initCKEditor(selector, height) {
                                    CKEDITOR.ClassicEditor
                                        .create(document.querySelector(selector), {
                                            htmlSupport: {
                                                allow: [{
                                                    name: /.*/,
                                                    attributes: true,
                                                    classes: true,
                                                    styles: true
                                                }]
                                            },
                                            height: height,
                                            allowedContent: true,
                                            extraAllowedContent: 'iframe[*]; div[*]; span[*]; style;',
                                            clipboard: {
                                                pasteFilter: null
                                            },
                                            extraPlugins: ['MediaEmbed', 'Clipboard'],
                                            toolbar: {
                                                items: [
                                                    'undo', 'redo', '|', 'bold', 'italic', '|',
                                                    'bulletedList', 'numberedList', '|', 'link', 'uploadImage',
                                                    'blockQuote', 'insertTable', 'mediaEmbed'
                                                ],
                                                shouldNotGroupWhenFull: true,
                                            },
                                            mediaEmbed: {
                                                previewsInData: true
                                            },
                                            removePlugins: [
                                                'AIAssistant', 'MultiLevelList', 'RealTimeCollaborativeComments',
                                                'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory',
                                                'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                                                'RevisionHistory', 'Pagination', 'WProofreader', 'MathType',
                                                'SlashCommand', 'Template', 'DocumentOutline', 'FormatPainter',
                                                'TableOfContents', 'PasteFromOfficeEnhanced', 'CaseChange'
                                            ],
                                        })
                                        .then(editor => console.log(`CKEditor ${selector} đã khởi tạo thành công!`))
                                        .catch(error => console.error(`Lỗi khi khởi tạo CKEditor ${selector}:`, error));
                                }

                                // Khởi tạo CKEditor cho cả hai trường
                                initCKEditor('#ck_short_description', 200);
                                initCKEditor('#ck_description', 300);
                            </script>




                            <!-- Giá -->
                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" required
                                        value="{{ old('price', $product->price) }}">
                                </div>
                            </div>

                            <!-- Giá khuyến mãi -->
                            <div class="row mb-3">
                                <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price_sale" name="price_sale"
                                        value="{{ old('price_sale', $product->price_sale) }}">
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="row mb-3">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        required value="{{ old('quantity', $product->quantity) }}">
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1">Kích hoạt</option>
                                        <option value="0">Vô hiệu hóa</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Có biến thể -->
                            <div class="row mb-3">
                                <label for="is_variant" class="col-sm-2 col-form-label">Có biến thể</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="is_variant" name="is_variant" required
                                        onchange="toggleVariants(this)" {{ !$product->is_variant ? 'disabled' : '' }}>
                                        a <option value="1" {{ $product->is_variant ? 'selected' : '' }}>Có
                                        </option>
                                        <option value="0" {{ !$product->is_variant ? 'selected' : '' }}>Không
                                        </option>
                                    </select>
                                    @if (!$product->is_variant)
                                        <input type="hidden" name="is_variant" value="0">
                                    @endif
                                </div>
                            </div>

                            <div id="variants-section"
                                style="{{ $product->variants->isNotEmpty() ? '' : 'display: none;' }}">
                                <h5 class="card-title">Thông tin biến thể</h5>
                                <a href="{{ route('variants.create', $product->id) }}" class="btn btn-primary mb-3">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm biến thể
                                </a>
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Giá</th>
                                            <th>Giá KM</th>
                                            <th>Số lượng</th>
                                            <th>Trạng thái</th>
                                            <th>Ảnh</th>
                                            <th>Thuộc tính</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->sku }}</td>
                                                <td>{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                                                <td>{{ number_format($variant->price_sale, 0, ',', '.') }}đ</td>
                                                <td>{{ $variant->quantity }}</td>
                                                <td>
                                                    @if ($variant->status)
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-secondary">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($variant->image)
                                                        <img src="{{ Storage::url($variant->image) }}"
                                                            style="max-width:80px;" />
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Get unique attributes for this variant
                                                        $variantAttributes = $variant->attributeValues
                                                            ->pluck('attribute')
                                                            ->unique('id');
                                                    @endphp
                                                    @foreach ($variantAttributes as $attribute)
                                                        <div>
                                                            <strong>{{ $attribute->name }}:</strong>
                                                            @php
                                                                $values = $variant->attributeValues
                                                                    ->where('attribute_id', $attribute->id)
                                                                    ->pluck('value')
                                                                    ->toArray();
                                                            @endphp
                                                            {{ implode(', ', $values) }}
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="1px" class="text-nowrap">
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('variants.show', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                            class="btn btn-secondary btn-sm">Xem</a>
                                                        <a href="{{ route('variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                            class="btn btn-info btn-sm">Sửa</a>
                                                        <form
                                                            action="{{ route('variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div> <!-- end of card-body -->
                </div> <!-- end of card -->
                <div class="row mb-3 mt-3">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-warning">Cập nhật</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
                </form><!-- End Form Thêm Mới Sản Phẩm -->
            </div>
    </section>
</main>

<script>
    let variantIndex = {{ count($product->variants) }};

    function toggleVariants(select) {
        const variantsSection = document.getElementById('variants-section');
        const quantitySection = document.getElementById('quantity-section');
        const priceInput = document.getElementById('price');
        const priceSaleInput = document.getElementById('price_sale');
        const quantityInput = document.getElementById('quantity');

        if (select.value === '1') {
            variantsSection.style.display = 'block';
            quantitySection.style.display = 'none';
            priceInput.required = false;
            priceSaleInput.required = false;
            quantityInput.required = false;
        } else {
            variantsSection.style.display = 'none';
            quantitySection.style.display = 'block';
            priceInput.required = true;
            priceSaleInput.required = false;
            quantityInput.required = true;
        }
    }

    function addVariantRow() {
        const tbody = document.getElementById('variants-table-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="variants[${variantIndex}][sku]" class="form-control" required></td>
            <td><input type="number" name="variants[${variantIndex}][price]" class="form-control" required></td>
            <td><input type="number" name="variants[${variantIndex}][price_sale]" class="form-control"></td>
            <td><input type="number" name="variants[${variantIndex}][quantity]" class="form-control" required></td>
            <td>
                <select name="variants[${variantIndex}][status]" class="form-select">
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </td>
            <td>
                <input type="file" name="variants[${variantIndex}][image]" class="form-control" accept="image/*" onchange="previewVariantImage(event, ${variantIndex})">
                <img id="variant-image-preview-${variantIndex}" style="max-width:80px; margin-top:5px; display:none;" />
            </td>
            <td>
                @foreach ($attributes as $attribute)
                    <div>
                        <strong>{{ $attribute->name }}:</strong>
                        @foreach ($attribute->attributeValues as $value)
                            <label class="me-2">
                                <input type="checkbox"
                                    name="variants[${variantIndex}][attributes][{{ $attribute->id }}][]"
                                    value="{{ $value->id }}">
                                {{ $value->value }}
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeVariantRow(this)">Xóa</button>
            </td>
        `;
        tbody.appendChild(row);
        variantIndex++;
    }

    function removeVariantRow(btn) {
        btn.closest('tr').remove();
    }

    function previewVariantImage(event, variantIndex) {
        const input = event.target;
        const preview = document.getElementById(`variant-image-preview-${variantIndex}`);
        if (input.files && input.files[0]) {
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
</script>
