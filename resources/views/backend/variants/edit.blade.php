<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('variants.index', $variant->product->id) }}">Danh sách sản phẩm biến thể của
                        {{ $variant->product->name }}</a>
                </li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <form action="{{ route('variants.update', ['product' => $variant->product->id, 'variant' => $variant->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Mã SKU</label>
                <input type="text" class="form-control" name="sku" value="{{ $variant->sku }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" class="form-control" name="price" value="{{ old('price', $variant->price) }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" class="form-control" name="quantity"
                    value="{{ old('quantity', $variant->quantity) }}" required>
            </div>

            <div class="form-group">
                <label for="price_sale">Giá khuyến mãi</label>
                <input type="number" class="form-control" id="price_sale" name="price_sale"
                    value="{{ old('price_sale', $variant->price_sale) }}" step="0.01">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="1" {{ $variant->status ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ !$variant->status ? 'selected' : '' }}>Vô hiệu hóa</option>
                </select>
            </div>

            <!-- Chọn thuộc tính section -->
            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Chọn thuộc tính</label>
                <div class="col-sm-10">
                    <div class="p-3 border rounded bg-light">
                        <div class="row">
                            @php
                                $selectedAttributeIds = isset($variant)
                                    ? $variant->attributeValues->pluck('attribute_id')->unique()->toArray()
                                    : [];
                            @endphp
                            @foreach ($attributes as $attribute)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input attribute-selector" type="checkbox"
                                            id="attr_type_{{ $attribute->id }}" value="{{ $attribute->id }}"
                                            name="attribute_types[]" data-attribute-name="{{ $attribute->name }}"
                                            @if (old('attribute_types') && in_array($attribute->id, old('attribute_types'))) checked
                                            @elseif (isset($variant) && in_array($attribute->id, $selectedAttributeIds))
                                                checked @endif>
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

            <!-- Variant details section -->
            <div class="row mb-3">
                <label for="attributes" class="col-sm-2 col-form-label">Thuộc tính</label>
                <div class="col-sm-10">
                    <div class="row">
                        @foreach ($attributes as $attribute)
                            @php
                                // Get the selected value from the pivot table
                                $selectedValue = old('attributes.' . $attribute->id);

                                // If no old input, get from pivot table
                                if ($selectedValue === null && isset($variant)) {
                                    $selectedValue = $variant
                                        ->attributeValues()
                                        ->where('attribute_id', $attribute->id)
                                        ->value('attribute_value_id');
                                }

                                // Ensure we have a string
                                $selectedValue = $selectedValue !== null ? (string) $selectedValue : '';
                            @endphp
                            <div class="col-md-6 mb-3 attribute-group" data-attribute-id="{{ $attribute->id }}"
                                style="display: none;">
                                <div class="p-2 border rounded bg-light">
                                    <div class="fw-bold mb-2">{{ $attribute->name }}</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($attribute->attributeValues as $value)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    id="attribute_{{ $attribute->id }}_{{ $value->id }}_edit"
                                                    name="attributes[{{ $attribute->id }}]"
                                                    value="{{ $value->id }}"
                                                    {{ $selectedValue === (string) $value->id ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="attribute_{{ $attribute->id }}_{{ $value->id }}_edit">{{ $value->value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="image" class="col-sm-2 col-form-label">Ảnh biến thể</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="image" accept="image/*"
                        onchange="previewVariantImage(event, 'edit')">
                    @if (isset($variant) && $variant->image)
                        <img id="variant-image-preview-edit" src="{{ Storage::url($variant->image) }}"
                            style="max-width:80px; margin-top:5px;" />
                    @else
                        <img id="variant-image-preview-edit" style="max-width:80px; margin-top:5px; display:none;" />
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Hủy</a>
        </form>


    </section>


</main>

<script>
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
        // On page load, show attribute groups for checked checkboxes
        attributeSelectors.forEach(selector => {
            if (selector.checked) {
                const attributeId = selector.value;
                const attributeGroups = document.querySelectorAll(
                    `.attribute-group[data-attribute-id="${attributeId}"]`);
                attributeGroups.forEach(group => {
                    group.style.display = 'block';
                    const radioInputs = group.querySelectorAll('input[type="radio"]');
                    radioInputs.forEach(input => {
                        input.required = true;
                    });
                });
            }
        });
    });
</script>
