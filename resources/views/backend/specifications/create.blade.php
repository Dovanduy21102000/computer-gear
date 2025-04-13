<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm thông số sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.specifications.index', ['product_id' => $product->id]) }}">Thông số sản phẩm</a>
                </li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thêm thông số cho sản phẩm: {{ $product->name }}</h5>

                        <form action="{{ route('admin.specifications.store', ['product_id' => $product->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div id="specifications-container">
                                <div class="specification-row d-flex gap-2 mb-3">
                                    <input type="text" class="form-control" name="specifications[0][key]" placeholder="Tên thông số" required>
                                    <input type="text" class="form-control" name="specifications[0][value]" placeholder="Giá trị" required>
                                    <button type="button" class="btn btn-danger remove-spec" style="display: none;">X</button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success" id="add-specification">Thêm thông số</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.specifications.index', ['product_id' => $product->id]) }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let specIndex = 1;

    document.getElementById("add-specification").addEventListener("click", function () {
        let container = document.getElementById("specifications-container");

        let newSpec = document.createElement("div");
        newSpec.classList.add("specification-row", "d-flex", "gap-2", "mb-3");
        newSpec.innerHTML = `
            <input type="text" class="form-control" name="specifications[${specIndex}][key]" placeholder="Tên thông số" required>
            <input type="text" class="form-control" name="specifications[${specIndex}][value]" placeholder="Giá trị" required>
            <button type="button" class="btn btn-danger remove-spec">X</button>
        `;

        container.appendChild(newSpec);
        specIndex++;
    });

    document.getElementById("specifications-container").addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-spec")) {
            event.target.parentElement.remove();
        }
    });
});
</script>
