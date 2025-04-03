<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý Album Ảnh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Album Ảnh</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách Ảnh trong Album</h5>
                        <a href="{{ route('backend.album.create', ['product_id' => $product->id]) }}" class="btn btn-primary mb-3">
                            <i class="bi bi-plus"></i> Thêm ảnh
                        </a>

                        <div class="image-gallery">
                            @foreach ($images as $image)
                                <div class="image-item">
                                    <img src="{{ Storage::url($image->image) }}" alt="Image" class="img-thumbnail">
                                    <div class="image-actions">
                                        <!-- Form xóa ảnh -->
                                        <form action="{{ route('backend.album.destroy', ['product_id' => $product->id, 'id' => $image->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    /* Chỉnh sửa chung cho giao diện */
    .main {
        margin: 0 auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
    }

    /* Chỉnh sửa tiêu đề trang */
    .pagetitle h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
    }

    /* Breadcrumb */
    .breadcrumb {
        background-color: #f8f9fa;
        padding: 0.75rem;
        border-radius: 5px;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Thêm kiểu dáng cho card */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    /* Nút "Thêm ảnh" */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    /* Gallery */
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .image-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .image-item:hover {
        transform: scale(1.05);
    }

    .img-thumbnail {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Nút xóa (btn-danger) */
    .image-actions {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .image-actions .btn {
        padding: 5px 12px;
        font-size: 13px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .image-actions .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .image-actions .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Nút Xóa icon */
    .image-actions .fa-trash {
        font-size: 14px;
    }
</style>
