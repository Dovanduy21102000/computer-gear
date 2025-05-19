{{-- <main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                  <a href="{{ route('variants.index', $variant->product->id) }}">Danh sách sản phẩm biến thể của {{ $variant->product->name }}</a>
               </li>             
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->  
        <section class="section">
            <form action="{{ route('variants.update', ['product' => $variant->product->id, 'variant' => $variant->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
                <div class="mb-3">
                    <label class="form-label">Mã SKU</label>
                    <input type="text" class="form-control" name="sku" value="{{ $variant->sku }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" class="form-control" name="price" value="{{ $variant->price }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $variant->quantity }}" required>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-control" name="status">
                        <option value="1" {{ $variant->status ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ !$variant->status ? 'selected' : '' }}>Vô hiệu hóa</option>
                    </select>
                </div>
            
                <div class="mb-3">
                    <label class="form-label">Ảnh sản phẩm:</label>
                    <div class="mb-2">
                        <img src="{{ Storage::url($variant->image) }}" alt="Ảnh sản phẩm" class="img-fluid rounded mb-3" style="max-width: 150px;">
                    </div>
                    <input type="file" class="form-control" name="image">
                </div>
            
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Hủy</a>
            </form>
            
      
        </section>
      
      
  </main> --}}
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết sản phẩm biến thể</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('variants.index', $variant->product->id) }}">
                        Danh sách sản phẩm biến thể của {{ $variant->product->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <!-- Block thông báo lỗi hiển thị ở đầu (như phần "thêm mới") -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('variants.update', ['product' => $variant->product->id, 'variant' => $variant->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Các trường cơ bản -->
            <div class="mb-3">
                <label class="form-label">Mã SKU</label>
                <input type="text" class="form-control" name="sku" value="{{ old('sku', $variant->sku) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $variant->price) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $variant->quantity) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="1" {{ old('status', $variant->status) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ old('status', $variant->status) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Ảnh sản phẩm:</label>
                <div class="mb-2">
                    <img src="{{ Storage::url($variant->image) }}" alt="Ảnh sản phẩm" class="img-fluid rounded mb-3" style="max-width: 150px;">
                </div>
                <input type="file" class="form-control" name="image">
            </div>
            
            <!-- Phần thông báo lỗi thuộc tính phía client -->
            <div id="attributeError" class="alert alert-danger" style="display: none;"></div>
            
            <!-- Nhóm thuộc tính -->
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
                                <button type="button" class="btn btn-danger remove-attribute">Xóa</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Hiển thị thuộc tính hiện tại của biến thể --}}
                    @foreach($variant->attributeValues as $index => $attrValue)
                        @php
                            $attributeKey = $attrValue->attribute->id;
                            $valueId = $attrValue->id;
                        @endphp
                        <div class="row mb-3 attribute-group">
                            <div class="col-sm-4">
                                <select name="attributes[{{ $index }}][key]" class="form-select attribute-key" required>
                                    <option value="">Chọn thuộc tính</option>
                                    @foreach($attributes as $att)
                                        <option value="{{ $att->id }}" {{ $att->id == $attributeKey ? 'selected' : '' }}>
                                            {{ $att->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="attributes[{{ $index }}][value]" class="form-select attribute-value" required>
                                    <option value="">Chọn giá trị</option>
                                    @php
                                        $selectedAtt = $attributes->firstWhere('id', $attributeKey);
                                    @endphp
                                    @if($selectedAtt)
                                        @foreach($selectedAtt->attributeValues as $val)
                                            <option value="{{ $val->id }}" {{ $val->id == $valueId ? 'selected' : '' }}>
                                                {{ $val->value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger remove-attribute">Xóa</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- Nút thêm nhóm thuộc tính -->
            <div class="row mb-3">
                <div class="col-sm-12">
                    <button type="button" id="addAttributeBtn" class="btn btn-primary">Thêm thuộc tính</button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('variants.index', $variant->product->id) }}" class="btn btn-secondary">Hủy</a>
        </form>
    </section>
</main>

<!-- Chuyển dữ liệu attributeValues từ backend sang JS -->
<script>
    window.attributeValues = @json(
        $attributes->pluck('attributeValues', 'id')
                   ->map(function($values) {
                      return $values->map(function($v){ 
                         return ['id' => $v->id, 'value' => $v->value];
                      });
                   })
    );
</script>

<!-- Script xử lý thêm, xóa, cập nhật dropdown thuộc tính và kiểm tra duplicate phía client -->
<script>
    // Khởi tạo index dựa trên số lượng nhóm thuộc tính hiện có
    let attributeIndex = {{ old('attributes') ? count(old('attributes')) : count($variant->attributeValues) }};

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
                <button type="button" class="btn btn-danger remove-attribute">Xóa</button>
            </div>
        `;
        container.appendChild(rowDiv);
        
        // Cập nhật dropdown giá trị khi thay đổi thuộc tính key
        const attributeKeySelect = rowDiv.querySelector(".attribute-key");
        attributeKeySelect.addEventListener("change", function() {
            const selectedAttributeId = this.value;
            const attributeValueSelect = rowDiv.querySelector(".attribute-value");
            attributeValueSelect.innerHTML = '<option value="">Chọn giá trị</option>';
            
            if(window.attributeValues[selectedAttributeId]) {
                window.attributeValues[selectedAttributeId].forEach(val => {
                    const opt = document.createElement("option");
                    opt.value = val.id;
                    opt.innerText = val.value;
                    attributeValueSelect.appendChild(opt);
                });
            }
        });
    });

    // Xử lý xóa nhóm thuộc tính
    document.addEventListener("click", function(event) {
        if (event.target && event.target.classList.contains("remove-attribute")) {
            event.target.closest(".attribute-group").remove();
        }
    });

    // Kiểm tra duplicate attribute key phía client
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('attribute-key')) {
            const attributeSelects = document.querySelectorAll('.attribute-key');
            let keys = [];
            let duplicateFound = false;
            
            attributeSelects.forEach(select => {
                const val = select.value;
                if(val) {
                    if(keys.indexOf(val) !== -1) {
                        duplicateFound = true;
                    } else {
                        keys.push(val);
                    }
                }
            });
            
            const errorDiv = document.getElementById('attributeError');
            if (duplicateFound) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Bạn không thể chọn cùng một thuộc tính nhiều hơn một lần.';
            } else {
                errorDiv.style.display = 'none';
                errorDiv.textContent = '';
            }
        }
    });
</script>



