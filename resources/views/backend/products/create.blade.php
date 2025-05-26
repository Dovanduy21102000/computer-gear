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
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="">Chọn danh mục</option>
                                        @php
                                            function renderCategoryOptions(
                                                $categories,
                                                $parentId = null,
                                                $parentName = null,
                                            ) {
                                                foreach ($categories as $category) {
                                                    if ($category['parent_id'] == $parentId) {
                                                        $hasChildren =
                                                            collect($categories)
                                                                ->where('parent_id', $category['id'])
                                                                ->count() > 0;
                                                        // Only show parent label if this is a child and has children
                                                        $displayName =
                                                            $parentName && $hasChildren
                                                                ? "{$category['name']} ({$parentName})"
                                                                : $category['name'];
                                                        if ($hasChildren) {
                                                            echo "<optgroup label=\"{$displayName}\">";
                                                            renderCategoryOptions(
                                                                $categories,
                                                                $category['id'],
                                                                $category['name'],
                                                            );
                                                            echo '</optgroup>';
                                                        } else {
                                                            echo "<option value=\"{$category['id']}\">{$displayName}</option>";
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @php renderCategoryOptions($allCategories); @endphp
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
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Tên sản phẩm -->
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required data-slug-generator>
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="{{ old('slug') }}" data-slug-target="#slug">
                                </div>
                            </div>

                            <!-- SKU -->
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ old('sku') }}" required>
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
                            <!-- Giá và Giá khuyến mãi -->
                            <div id="price-section">
                                <div class="row mb-3">
                                    <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control @error('price') is-invalid @enderror" id="price"
                                            name="price" value="{{ old('price') }}">
                                        <span id="price-error" class="text-danger" style="display:none;">Vui lòng
                                            nhập giá.</span>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control @error('price_sale') is-invalid @enderror"
                                            id="price_sale" name="price_sale" value="{{ old('price_sale') }}">
                                        @error('price_sale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="quantity-section">
                                <div class="row mb-3">
                                    <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control @error('quantity') is-invalid @enderror"
                                            id="quantity" name="quantity" value="{{ old('quantity') }}">
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Kích
                                            hoạt</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Vô hiệu
                                            hóa</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Phần Nhập Liệu Cho Biến Thể -->
                            <div id="variants-section" style="display: none;">
                                <h5 class="card-title text-primary"><i class="bi bi-layers me-1"></i> Thông tin biến
                                    thể</h5>

                                <!-- Attribute Type Selection -->
                                <div class="row mb-4">
                                    <label class="col-sm-2 col-form-label">Chọn thuộc tính</label>
                                    <div class="col-sm-10">
                                        <div class="p-3 border rounded bg-light">
                                            <div class="row">
                                                @foreach ($attributes as $attribute)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input attribute-selector"
                                                                type="checkbox" id="attr_type_{{ $attribute->id }}"
                                                                value="{{ $attribute->id }}"
                                                                data-attribute-name="{{ $attribute->name }}">
                                                            <label class="form-check-label"
                                                                for="attr_type_{{ $attribute->id }}">
                                                                {{ $attribute->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="variants">
                                    <div class="variant mb-4 bg-white shadow-sm p-3 rounded">
                                        <div class="row mb-3">
                                            <label for="variants[0][sku]" class="col-sm-2 col-form-label">SKU Biến
                                                thể</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="variants[0][sku]"
                                                    value="{{ old('variants.0.sku') }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][price]" class="col-sm-2 col-form-label">Giá Biến
                                                thể</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="variants[0][price]"
                                                    value="{{ old('variants.0.price') }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][price_sale]" class="col-sm-2 col-form-label">Giá
                                                khuyến mãi</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control"
                                                    name="variants[0][price_sale]"
                                                    value="{{ old('variants.0.price_sale') }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][quantity]" class="col-sm-2 col-form-label">Số
                                                lượng Biến thể</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control"
                                                    name="variants[0][quantity]"
                                                    value="{{ old('variants.0.quantity') }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][image]" class="col-sm-2 col-form-label">Ảnh biến
                                                thể</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="variants[0][image]"
                                                    accept="image/*" onchange="previewVariantImage(event, 0)">
                                                <img id="variant-image-preview-0"
                                                    style="max-width:80px; margin-top:5px; display:none;" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc
                                                tính</label>
                                            <div class="col-sm-10">
                                                <div class="row" id="variant-attributes-0">
                                                    @foreach ($attributes as $attribute)
                                                        <div class="col-md-6 mb-3 attribute-group"
                                                            data-attribute-id="{{ $attribute->id }}"
                                                            style="display: none;">
                                                            <div class="p-2 border rounded bg-light">
                                                                <div class="fw-bold mb-2">{{ $attribute->name }}</div>
                                                                <div class="d-flex flex-wrap gap-2 attribute-values-scroll"
                                                                    style="min-height: 80px; max-height: 650px; overflow-y: auto;">
                                                                    @foreach ($attribute->attributeValues as $value)
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input"
                                                                                type="radio"
                                                                                id="attribute_{{ $attribute->id }}_{{ $value->id }}_0"
                                                                                name="variants[0][attributes][{{ $attribute->id }}]"
                                                                                value="{{ $value->id }}">
                                                                            <label class="form-check-label"
                                                                                for="attribute_{{ $attribute->id }}_{{ $value->id }}_0">
                                                                                {{ $value->value }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mb-3">
                                    <button type="button" class="btn btn-primary" onclick="addVariant()">
                                        <i class="bi bi-plus-circle me-1"></i> Thêm biến thể
                                    </button>
                                </div>
                            </div>

                            <!-- Nút Submit -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="bi bi-plus-circle me-1"></i> Thêm sản phẩm</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary"><i
                                            class="bi bi-x-circle me-1"></i> Hủy bỏ</a>
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
    let attributeGroupCount = 0;
    document.addEventListener('DOMContentLoaded', function() {
        const baseSKU = document.getElementById('sku').value;
        const variantSKUInput = document.querySelector('input[name="variants[0][sku]"]');

        if (variantSKUInput) {
            const variantSKU = `${baseSKU}-1`;
            variantSKUInput.value = variantSKU;
        }

        // Attribute type checkbox logic
        document.querySelectorAll('.attribute-selector').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const attributeId = this.value;
                document.querySelectorAll(
                    `.attribute-group[data-attribute-id='${attributeId}']`).forEach(
                    group => {
                        group.style.display = this.checked ? 'block' : 'none';
                        // Set required for radios in this group
                        group.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.required = this.checked;
                        });
                    });
            });
        });
    });

    function toggleVariants(select) {
        const variantsSection = document.getElementById('variants-section');
        const quantitySection = document.getElementById('quantity-section');
        const priceSection = document.getElementById('price-section');
        const priceInput = document.getElementById('price');
        const priceSaleInput = document.getElementById('price_sale');
        const quantityInput = document.getElementById('quantity');

        // Get all input elements within the variants section
        const variantInputs = variantsSection.querySelectorAll('input, select, textarea');

        if (select.value === '1') { // '1' means "Has variants"
            variantsSection.style.display = 'block';
            quantitySection.style.display = 'none';
            priceSection.style.display = 'none';

            // Enable variant inputs and remove required from non-variant inputs
            variantInputs.forEach(input => {
                input.removeAttribute('disabled');
                // Re-add required based on original markup or data attributes if needed
                // For simplicity, we assume required is set in the initial HTML for variants
            });
            priceInput.removeAttribute('required');
            priceSaleInput.removeAttribute('required');
            quantityInput.removeAttribute('required');

        } else { // '0' means "Does not have variants"
            variantsSection.style.display = 'none';
            quantitySection.style.display = 'block';
            priceSection.style.display = 'block';

            // Disable variant inputs and set required for non-variant inputs
            variantInputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
            });
            priceInput.setAttribute('required', 'required');
            priceSaleInput.removeAttribute('required'); // price_sale is optional
            quantityInput.setAttribute('required', 'required');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('is_variant');
        if (variantSelect) {
            toggleVariants(variantSelect);
        }

        // Restore selected attributes if any
        const oldAttributes = @json(old('variants.0.attributes', []));
        if (oldAttributes) {
            Object.keys(oldAttributes).forEach(attributeId => {
                const checkbox = document.querySelector(`#attr_type_${attributeId}`);
                if (checkbox) {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        }
    });

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

    function generateVariantSKU(baseSKU, attributes) {
        const randomSuffix = Math.floor(1000 + Math.random() * 9000);
        const attributePart = attributes.map(attr => attr.toUpperCase().substring(0, 11)).join('-');
        return `${baseSKU}-${attributePart}-${randomSuffix}`;
    }

    function addVariant() {
        const variantsDiv = document.getElementById('variants');
        const baseSKU = document.getElementById('sku').value;
        const newVariant = document.createElement('div');
        newVariant.classList.add('variant', 'mb-4', 'bg-white', 'shadow-sm', 'p-3', 'rounded');

        const variantSKU = `${baseSKU}-${variantCount+1}`;

        // Get selected attributes
        const selectedAttributes = Array.from(document.querySelectorAll('.attribute-selector:checked'))
            .map(checkbox => checkbox.value);

        newVariant.innerHTML = `
            <div class="row mb-3">
                <label for="variants[${variantCount}][sku]" class="col-sm-2 col-form-label">SKU Biến thể</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="variants[${variantCount}][sku]" 
                        value="${variantSKU}" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][price]" class="col-sm-2 col-form-label">Giá Biến thể</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="variants[${variantCount}][price]" 
                        value="{{ old('variants.${variantCount}.price') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][price_sale]" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="variants[${variantCount}][price_sale]"
                        value="{{ old('variants.${variantCount}.price_sale') }}">
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][quantity]" class="col-sm-2 col-form-label">Số lượng Biến thể</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="variants[${variantCount}][quantity]"
                        value="{{ old('variants.${variantCount}.quantity') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][image]" class="col-sm-2 col-form-label">Ảnh biến thể</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="variants[${variantCount}][image]" accept="image/*" onchange="previewVariantImage(event, ${variantCount})">
                    <img id="variant-image-preview-${variantCount}" style="max-width:80px; margin-top:5px; display:none;" />
                </div>
            </div>
            <div class="row mb-3">
                <label for="variants[${variantCount}][attributes]" class="col-sm-2 col-form-label">Thuộc tính</label>
                <div class="col-sm-10">
                    <div class="row" id="variant-attributes-${variantCount}">
                        @foreach ($attributes as $attribute)
                            <div class="col-md-6 mb-3 attribute-group" data-attribute-id="{{ $attribute->id }}" style="display: none;">
                                <div class="p-2 border rounded bg-light">
                                    <div class="fw-bold mb-2">{{ $attribute->name }}</div>
                                    <div class="d-flex flex-wrap gap-2 attribute-values-scroll" style="min-height: 80px; max-height: 650px; overflow-y: auto;">
                                        @foreach ($attribute->attributeValues as $value)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    id="attribute_{{ $attribute->id }}_{{ $value->id }}_${variantCount}"
                                                    name="variants[${variantCount}][attributes][{{ $attribute->id }}]"
                                                    value="{{ $value->id }}"
                                                    {{ old('variants.${variantCount}.attributes.' . $attribute->id) == $value->id ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attribute_{{ $attribute->id }}_{{ $value->id }}_${variantCount}">
                                                    {{ $value->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-trash me-1"></i> Xóa biến thể
                </button>
            </div>
        `;

        variantsDiv.appendChild(newVariant);

        // Show only selected attributes in the new variant
        selectedAttributes.forEach(attributeId => {
            const attributeGroup = newVariant.querySelector(
                `.attribute-group[data-attribute-id="${attributeId}"]`);
            if (attributeGroup) {
                attributeGroup.style.display = 'block';
                const radioInputs = attributeGroup.querySelectorAll('input[type="radio"]');
                radioInputs.forEach(input => {
                    input.required = true;
                });
            }
        });

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

@push('styles')
    <style>
        .select2-results__option {
            font-family: 'Fira Mono', 'Consolas', 'Menlo', 'Monaco', monospace;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#category_id').treeselect({
                search: true,
                placeholder: 'Chọn danh mục',
            });
        });
    </script>
@endpush
