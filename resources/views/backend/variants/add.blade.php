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
                            <div class="row mb-3">
                                <label for="sku" class="col-sm-2 col-form-label">SKU biến thể</label>
                                <div class="col-sm-10">
                                    <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="price" class="col-sm-2 col-form-label">Giá</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" name="price_sale" id="price_sale" class="form-control" value="{{ old('price_sale') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="quantity" class="col-sm-2 col-form-label">Số lượng</label>
                                <div class="col-sm-10">
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="image" class="col-sm-2 col-form-label">Ảnh biến thể</label>
                                <div class="col-sm-10">
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                            </div>
                            <div id="dynamicAttributes">
                                @if(old('attributes'))
                                    @foreach(old('attributes') as $index => $attribute)
                                        <div class="row mb-3 attribute-group">
                                            <div class="col-sm-4">
                                                <select name="attributes[{{ $index }}][key]" class="form-select attribute-key" required>
                                                    <option value="">Chọn thuộc tính</option>
                                                    @foreach($attributes as $att)
                                                        <option value="{{ $att->id }}" {{ (isset($attribute['key']) && $attribute['key'] == $att->id) ? 'selected' : '' }}>
                                                            {{ $att->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select name="attributes[{{ $index }}][value]" class="form-select attribute-value" required>
                                                    <option value="">Chọn giá trị</option>
                                                    @if(isset($attribute['key']) && $attribute['key'])
                                                        @php
                                                            $selectedAtt = $attributes->firstWhere('id', $attribute['key']);
                                                        @endphp
                                                        @if($selectedAtt)
                                                            @foreach($selectedAtt->attributeValues as $val)
                                                                <option value="{{ $val->id }}" {{ (isset($attribute['value']) && $attribute['value'] == $val->id) ? 'selected' : '' }}>
                                                                    {{ $val->value }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-danger remove-attribute">
                                                    Xóa
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <!-- Nút thêm nhóm thuộc tính -->
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <button type="button" id="addAttributeBtn" class="btn btn-primary">
                                        Thêm thuộc tính
                                    </button>
                                </div>
                            </div>

                            <!-- Nút lưu form -->
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

<!-- Script: Truyền dữ liệu các giá trị cho từng thuộc tính từ server sang JS -->
<script>
    // Dữ liệu được truyền theo định dạng:
    // { "attributeID": [ { id: ..., value: 'Giá trị' }, ... ], ... }
    window.attributeValues = @json(
        $attributes->pluck('attributeValues', 'id')->map(function($values) {
            return $values->map(function($v){ 
                return ['id' => $v->id, 'value' => $v->value];
            });
        })
    );
</script>

<!-- Script: Xử lý thêm, cập nhật và xóa nhóm thuộc tính động -->
<script>
    // Nếu có dữ liệu old, bắt đầu từ số nhóm đã có, ngược lại khởi tạo từ 0.
    let attributeIndex = {{ old('attributes') ? count(old('attributes')) : 0 }};

    document.getElementById("addAttributeBtn").addEventListener("click", function() {
        attributeIndex++; // Tăng chỉ số cho nhóm mới
        const container = document.getElementById("dynamicAttributes");
        
        // Tạo nhóm thuộc tính mới
        const rowDiv = document.createElement("div");
        rowDiv.className = "row mb-3 attribute-group";
        rowDiv.innerHTML = `
            <div class="col-sm-4">
                <select name="attributes[${attributeIndex}][key]" class="form-select attribute-key" required>
                    <option value="">Chọn thuộc tính</option>
                    @foreach($attributes as $att)
                        <option value="{{ $att->id }}">{{ $att->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <select name="attributes[${attributeIndex}][value]" class="form-select attribute-value" required>
                    <option value="">Chọn giá trị</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger remove-attribute">
                    Xóa
                </button>
            </div>
        `;
        container.appendChild(rowDiv);
        
        // Xử lý cập nhật danh sách giá trị khi chọn key
        const attributeKeySelect = rowDiv.querySelector(".attribute-key");
        attributeKeySelect.addEventListener("change", function() {            
            const selectedAttributeId = this.value;
            const attributeValueSelect = rowDiv.querySelector(".attribute-value");
            // Reset lại danh sách lựa chọn giá trị
            attributeValueSelect.innerHTML = '<option value="">Chọn giá trị</option>';
            
            if (window.attributeValues[selectedAttributeId]) {
                window.attributeValues[selectedAttributeId].forEach(val => {
                    const opt = document.createElement("option");
                    opt.value = val.id;
                    opt.innerText = val.value;
                    attributeValueSelect.appendChild(opt);
                });
            }
        });
    });
    
    // Xử lý nút xóa cho các nhóm thuộc tính (dùng event delegation)
    document.addEventListener("click", function(event) {
        if (event.target && event.target.classList.contains("remove-attribute")) {
            event.target.closest(".attribute-group").remove();
        }
    });
</script>
