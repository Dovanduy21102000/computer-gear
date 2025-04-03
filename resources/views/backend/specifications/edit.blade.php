<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chỉnh sửa thông số sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.specifications.index', ['product_id' => $product->id]) }}">Thông số sản
                        phẩm</a>
                </li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Chỉnh sửa thông số cho sản phẩm: {{ $product->name }}</h5>

                        <form action="{{ route('admin.specifications.bulkUpdate', ['product_id' => $product->id]) }}"
                            method="POST">

                            @csrf
                            @method('PUT')

                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <input type="hidden" name="deleted_specifications" id="deleted_specifications">
                            <div id="specifications-container">
                                @foreach ($specifications as $index => $spec)
                                    <div class="specification-row d-flex gap-2 mb-3" data-id="{{ $spec->id }}">
                                        <input type="hidden" name="specifications[{{ $index }}][id]"
                                            value="{{ $spec->id }}">
                                        <input type="text" class="form-control"
                                            name="specifications[{{ $index }}][key]" value="{{ $spec->key }}"
                                            required>
                                        <input type="text" class="form-control"
                                            name="specifications[{{ $index }}][value]"
                                            value="{{ $spec->value }}" required>
                                        <button type="button" class="btn btn-danger remove-spec">X</button>
                                    </div>
                                @endforeach
                            </div>


                            <button type="button" class="btn btn-success" id="add-specification">Thêm thông số</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.specifications.index', ['product_id' => $product->id]) }}"
                                class="btn btn-secondary">Quay lại</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    let specIndex = {{ count($specifications) }};
    let deletedSpecifications = [];

    // Xử lý thêm thông số mới
    document.getElementById("add-specification").addEventListener("click", function() {
        let container = document.getElementById("specifications-container");

        let newSpec = document.createElement("div");
        newSpec.classList.add("specification-row", "d-flex", "gap-2", "mb-3");
        newSpec.innerHTML = `
            <input type="hidden" name="specifications[${specIndex}][id]" value="">
            <input type="text" class="form-control" name="specifications[${specIndex}][key]" placeholder="Tên thông số" required>
            <input type="text" class="form-control" name="specifications[${specIndex}][value]" placeholder="Giá trị" required>
            <button type="button" class="btn btn-danger remove-spec">X</button>
        `;

        container.appendChild(newSpec);
        specIndex++;
    });

    // Xử lý xóa thông số khi ấn "X"
    document.getElementById("specifications-container").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-spec")) {
            let row = event.target.closest(".specification-row");

            if (!row) return; // Nếu không tìm thấy hàng thì thoát

            let specId = row.getAttribute("data-id");

            if (specId) {
                deletedSpecifications.push(specId);
                document.getElementById("deleted_specifications").value = JSON.stringify(deletedSpecifications);
            }

            row.remove(); // Ẩn hàng bị xóa
        }
    });
});

</script>
