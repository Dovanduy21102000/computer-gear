<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm ảnh vào album sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backend.album.index', ['product_id' => $product->id]) }}">Album sản phẩm</a>
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
                        <h5 class="card-title">Thêm ảnh cho sản phẩm: {{ $product->name }}</h5>

                        <!-- Form để thêm ảnh -->
                        <form action="{{ route('backend.album.store', ['product_id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="mb-3">
                                <label for="image" class="form-label">Chọn ảnh</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('backend.album.index', ['product_id' => $product->id]) }}" class="btn btn-secondary">Quay lại</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
