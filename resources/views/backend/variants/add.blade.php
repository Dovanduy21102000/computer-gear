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
                        <h5 class="card-title">ewqeqw</h5>
                        <form action="{{ route('variants.store', ['product' => $product->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Chọn thuộc tính section -->
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
                                                            for="attr_type_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Chọn thuộc tính section -->

                            <div id="variants">
                                <div class="variant mb-4">
                                    <div class="row mb-3">
                                        <label for="sku" class="col-sm-2 col-form-label">SKU Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="sku" id="variant-sku"
                                                value="{{ old('sku', $suggestedSku ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="price" class="col-sm-2 col-form-label">Giá Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="price"
                                                value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="price_sale"
                                                value="{{ old('price_sale') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="quantity" class="col-sm-2 col-form-label">Số lượng Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="quantity"
                                                value="{{ old('quantity') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="image" class="col-sm-2 col-form-label">Ảnh biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" name="image" accept="image/*"
                                                onchange="previewVariantImage(event, 0)">
                                            <img id="variant-image-preview-0"
                                                style="max-width:80px; margin-top:5px; display:none;" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc
                                            tính</label>
                                        <div class="col-sm-10">
                                            <div class="row">
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
                                                                        <input class="form-check-input" type="radio"
                                                                            id="attribute_{{ $attribute->id }}_{{ $value->id }}_0"
                                                                            name="attributes[{{ $attribute->id }}]"
                                                                            value="{{ $value->id }}"
                                                                            {{ old('attributes.' . $attribute->id) == $value->id ? 'checked' : '' }}>
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
                            <button type="submitsubmit" class="btn btn-secondary">Thêm biến thể</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>

<script>
    let attributeGroupCount = 0;

    function addAttributeGroup() {
        const container = document.getElementById('dynamic-attributes');
        const groupIndex = attributeGroupCount++;
        const groupDiv = document.createElement('div');
        groupDiv.className = 'border rounded p-3 mb-3 bg-light';
        groupDiv.innerHTML = `
        <div class="mb-2 d-flex align-items-center">
            <input type="text" class="form-control me-2" name="new_attributes[${groupIndex}][name]" placeholder="Tên thuộc tính (VD: RAM, ROM, Màu...)" required style="max-width: 250px;">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="bi bi-trash"></i></button>
        </div>
        <div id="attribute-values-${groupIndex}" class="d-flex flex-wrap gap-2 mb-2"></div>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addAttributeValue(${groupIndex})">+ Thêm giá trị</button>
    `;
        container.appendChild(groupDiv);
    }

    function addAttributeValue(groupIndex) {
        const valuesDiv = document.getElementById(`attribute-values-${groupIndex}`);
        const valueIndex = valuesDiv.children.length;
        const valueDiv = document.createElement('div');
        valueDiv.className = 'input-group input-group-sm mb-1';
        valueDiv.style.maxWidth = '180px';
        valueDiv.innerHTML = `
        <input type="text" class="form-control" name="new_attributes[${groupIndex}][values][]" placeholder="Giá trị (VD: 8GB, Đỏ...)" required>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
    `;
        valuesDiv.appendChild(valueDiv);
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

    // Attribute type selection logic
    document.addEventListener('DOMContentLoaded', function() {
        const attributeSelectors = document.querySelectorAll('.attribute-selector');
        attributeSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const attributeId = this.value;
                const attributeGroups = document.querySelectorAll(
                    `.attribute-group[data-attribute-id="${attributeId}"]`);
                attributeGroups.forEach(group => {
                    group.style.display = this.checked ? 'block' : 'none';
                    const radioInputs = group.querySelectorAll('input[type="radio"]');
                    radioInputs.forEach(input => {
                        input.required = this.checked;
                    });
                });
            });
        });
    });
</script>
