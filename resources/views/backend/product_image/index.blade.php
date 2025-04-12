<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý Album Ảnh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Album Ảnh</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách Ảnh trong Album của: <strong>{{ $product->name }}</strong></h5>

                        <a href="{{ route('backend.product_images.edit', ['product_id' => $product->id]) }}" class="btn btn-warning mb-3">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa Album
                        </a>
                        
                        
                        <div class="image-gallery">
                            @forelse ($images as $index => $image)
                                <div class="image-item">
                                    <img src="{{ Storage::url($image) }}" alt="Image" class="img-thumbnail">
                                </div>
                            @empty
                                <p class="text-muted">Chưa có ảnh nào trong album.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .main {
        margin: 0 auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
    }

    .pagetitle h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
    }

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

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
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
        transform: scale(1.03);
    }

    .img-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
