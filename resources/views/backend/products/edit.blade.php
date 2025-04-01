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
                        <form action="{{ route('products.update', $product->id) }}" method="PUT"
                            enctype="multipart/form-data">
                            @csrf

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
                                        onchange="toggleVariants(this)">
                                        <option value="1">Có</option>
                                        <option value="0" selected>Không</option>
                                    </select>
                                </div>
                            </div>

                            <div id="variants-section" style="{{ $product->variants->isNotEmpty() ? '' : 'display: none;' }}">
                                <h5 class="card-title">Thông tin biến thể</h5>
                                <div id="variants">
                                    @foreach ($product->variants as $index => $variant)
                                        <div class="variant mb-4">
                                            <div class="row mb-3">
                                                <label for="variants[{{ $index }}][sku]" class="col-sm-2 col-form-label">SKU Biến thể</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="variants[{{ $index }}][sku]"
                                                        value="{{ old("variants.$index.sku", $variant->sku) }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="variants[{{ $index }}][price]" class="col-sm-2 col-form-label">Giá Biến thể</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" name="variants[{{ $index }}][price]"
                                                        value="{{ old("variants.$index.price", $variant->price) }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="variants[{{ $index }}][quantity]" class="col-sm-2 col-form-label">Số lượng Biến thể</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" name="variants[{{ $index }}][quantity]"
                                                        value="{{ old("variants.$index.quantity", $variant->quantity) }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="variants[{{ $index }}][attributes]" class="col-sm-2 col-form-label">Thuộc tính</label>
                                                <div class="col-sm-10">
                                                    <select class="form-select" name="variants[{{ $index }}][attributes][]" multiple required>
                                                        @foreach ($attributes as $attribute)
                                                            <optgroup label="{{ $attribute->name }}">
                                                                @foreach ($attribute->attributeValues as $value)
                                                                    <option value="{{ $value->id }}" 
                                                                        {{ in_array($value->id, old("variants.$index.attributes", $variant->attributes->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                                        {{ $value->value }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addVariant()">Thêm biến thể</button>
                            </div>
                            

                            <!-- Nút Submit -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </div>
                        </form><!-- End Form Thêm Mới Sản Phẩm -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    let variantCount = 1;

    function toggleVariants(select) {
        const variantsSection = document.getElementById('variants-section');
        if (select.value === '1') {
            variantsSection.style.display = 'block';
        } else {
            variantsSection.style.display = 'none';
        }
    }

    function addVariant() {
        const variantsDiv = document.getElementById('variants');
        const newVariant = document.createElement('div');
        newVariant.classList.add('variant', 'mb-4');
        newVariant.innerHTML = `
            <div class="row mb-3">
                <label for="variants[${variantCount}][sku]" class="col-sm-2 col-form-label">SKU Biến thể</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="variants[${variantCount}][sku]" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][price]" class="col-sm-2 col-form-label">Giá Biến thể</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="variants[${variantCount}][price]" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][quantity]" class="col-sm-2 col-form-label">Số lượng Biến thể</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="variants[${variantCount}][quantity]" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][attributes]" class="col-sm-2 col-form-label">Thuộc tính</label>
                <div class="col-sm-10">
                    <select class="form-select" name="variants[${variantCount}][attributes][]" multiple required>
                        @foreach ($attributes as $attribute)
                            <optgroup label="{{ $attribute->name }}">
                                @foreach ($attribute->attributeValues as $value)
                                    <option value="{{ $value->id }}">{{ $value->value }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        `;
        variantsDiv.appendChild(newVariant);
        variantCount++;
    }
</script>
