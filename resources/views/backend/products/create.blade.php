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
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2" style="font-size:1.5rem;"></i>
                        <div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin sản phẩm</h5>

                        <!-- Form Thêm Mới Sản Phẩm -->
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Danh mục -->
                            <div class="row mb-3">
                                <label for="category_id" class="col-sm-2 col-form-label">Danh mục</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Tên sản phẩm -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                                </div>
                            </div>

                            <!-- SKU -->
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
                                </div>
                            </div>

                            <!-- Ảnh đại diện -->
                            <div class="row mb-3">
                                <label for="thumbnail" class="col-sm-2 col-form-label">Ảnh đại diện</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                    <img id="thumb-preview" style="max-width:120px; margin-top:10px; display:none;" />
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
                            <!-- Mô tả ngắn -->
                            <div class="row mb-3">
                                <label for="short_description" class="col-sm-2 col-form-label">Mô tả ngắn</label>
                                <div class="col-sm-10">
                                    <textarea id="ck_short_description" name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
                                </div>
                            </div>

                            <!-- Mô tả chi tiết -->
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Mô tả chi tiết</label>
                                <div class="col-sm-10">
                                    <textarea id="ck_description" name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
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
                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}">
                                </div>
                            </div>

                            <!-- Giá khuyến mãi -->
                            <div class="row mb-3">
                                <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price_sale" name="price_sale" value="{{ old('price_sale') }}">
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="row mb-3" id="quantity-section">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}">
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                                    </select>
                                </div>
                            </div>



                            <!-- Phần Nhập Liệu Cho Biến Thể -->
                            <div id="variants-section" style="display: none;">
                                <h5 class="card-title text-primary"><i class="bi bi-layers me-1"></i> Thông tin biến thể</h5>
                                <div id="variants">
                                    <div class="variant mb-4 bg-white shadow-sm p-3 rounded">
                                        <div class="row mb-3">
                                            <label for="variants[0][sku]" class="col-sm-2 col-form-label">SKU Biến
                                                thể</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="variants[0][sku]" value="{{ old('variants.0.sku') }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][price]" class="col-sm-2 col-form-label">Giá Biến
                                                thể</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="variants[0][price]" value="{{ old('variants.0.price') }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][quantity]" class="col-sm-2 col-form-label">Số
                                                lượng Biến thể</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control"
                                                    name="variants[0][quantity]" value="{{ old('variants.0.quantity') }}">
                                            </div>
                                        </div>
                                        <!-- Có biến thể -->
                                        <div class="row mb-3">
                                            <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc
                                                tính</label>
                                            <div class="col-sm-10">
                                                @foreach ($attributes as $attribute)
                                                    <div class="mb-2">
                                                        <strong>{{ $attribute->name }}</strong>
                                                        <div>
                                                            @foreach ($attribute->attributeValues as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="attribute_{{ $attribute->id }}_{{ $value->id }}"
                                                                        name="variants[0][attributes][{{ $attribute->id }}]"
                                                                        value="{{ $value->id }}"
                                                                        @if (in_array($value->id, old('variants.0.attributes', []))) checked @endif>
                                                                    <label class="form-check-label"
                                                                        for="attribute_{{ $attribute->id }}_{{ $value->id }}">
                                                                        {{ $value->value }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addVariant()"><i class="bi bi-plus-circle me-1"></i> Thêm biến thể</button>
                            </div>

                            <!-- Nút Submit -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Hủy bỏ</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        const baseSKU = document.getElementById('sku').value;
        const variantSKUInput = document.querySelector('input[name="variants[0][sku]"]');

        if (variantSKUInput) {
            const variantSKU = `${baseSKU}-1`; // 
            variantSKUInput.value = variantSKU;
        }
    });
    document.getElementById('name').addEventListener('input', function() {
        const baseSKU = generateSKU(this.value);
        const skuInput = document.getElementById('sku');
        skuInput.value = baseSKU;

        // Cập nhật SKU cho biến thể đầu tiên
        const variantSKUInput = document.querySelector('input[name="variants[0][sku]"]');
        if (variantSKUInput) {
            const variantSKU = `${baseSKU}-1`;
            variantSKUInput.value = variantSKU;
        }
    });

    function toggleVariants(select) {
        const variantsSection = document.getElementById('variants-section');
        const quantitySection = document.getElementById('quantity-section');

        if (select.value === '1') {
            variantsSection.style.display = 'block';
            quantitySection.style.display = 'none';
        } else {
            variantsSection.style.display = 'none';
            quantitySection.style.display = 'block';
        }
    }

    function generateSlug(value) {
        return value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    document.getElementById('name').addEventListener('input', function() {
        const slugInput = document.getElementById('slug');
        slugInput.value = generateSlug(this.value);
    });

    function generateSKU(value) {
        const baseSKU = value
            .toUpperCase()
            .trim()
            .replace(/[^A-Z0-9]/g, '')
            .substring(0, 3);
        return baseSKU;
    }

    document.getElementById('name').addEventListener('input', function() {
        const baseSKU = generateSKU(this.value);
        const skuInput = document.getElementById('sku');
        skuInput.value = baseSKU;

        const variantSKUInput = document.querySelector('input[name="variants[0][sku]"]');
        if (variantSKUInput) {
            const variantSKU = `${baseSKU}-1`;
            variantSKUInput.value = variantSKU;
        }
    });

    function generateVariantSKU(baseSKU, attributes) {
        const randomSuffix = Math.floor(1000 + Math.random() * 9000); 
        const attributePart = attributes.map(attr => attr.toUpperCase().substring(0, 11)).join(
            '-'); 
        return `${baseSKU}-${attributePart}-${randomSuffix}`;
    }

    function addVariant() {
        const variantsDiv = document.getElementById('variants');
        const baseSKU = document.getElementById('sku').value;
        const newVariant = document.createElement('div');
        newVariant.classList.add('variant', 'mb-4');

        const variantSKU = `${baseSKU}-${variantCount+1}`;

        newVariant.innerHTML = `
        <div class="row mb-3">
            <label for="variants[${variantCount}][sku]" class="col-sm-2 col-form-label">SKU Biến thể</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="variants[${variantCount}][sku]" value="${variantSKU}" >
            </div>
        </div>
        <div class="row mb-3">
            <label for="variants[${variantCount}][price]" class="col-sm-2 col-form-label">Giá Biến thể</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="variants[${variantCount}][price]" >
            </div>
        </div>
        <div class="row mb-3">
            <label for="variants[${variantCount}][quantity]" class="col-sm-2 col-form-label">Số lượng Biến thể</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="variants[${variantCount}][quantity]">
            </div>
        </div>
        <div class="row mb-3">
                                            <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc
                                                tính</label>
                                            <div class="col-sm-10">
                                                @foreach ($attributes as $attribute)
                                                    <div class="mb-2">
                                                        <strong>{{ $attribute->name }}</strong>
                                                        <div>
                                                            @foreach ($attribute->attributeValues as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="attribute_{{ $value->id }}"
                                                                        name="variants[0][attributes][]"
                                                                        value="{{ $value->id }}"
                                                                        @if (in_array($value->id, old('variants.0.attributes', []))) checked @endif>
                                                                    <label class="form-check-label"
                                                                        for="attribute_{{ $value->id }}">
                                                                        {{ $value->value }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
    `;
        variantsDiv.appendChild(newVariant);
        variantCount++;
    }

    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const [file] = this.files;
        const preview = document.getElementById('thumb-preview');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
</script>
