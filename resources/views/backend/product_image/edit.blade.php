<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chỉnh sửa Album Ảnh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backend.product_images.index', ['product_id' => $product->id]) }}">Album sản phẩm</a>
                </li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Chỉnh sửa ảnh cho sản phẩm: <strong>{{ $product->name }}</strong></h5>

                        <form action="{{ route('backend.product_images.update', ['product_id' => $product->id, 'key' => 'all']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="deleted_images" id="deleted_images" value="[]">

                            <!-- Ảnh hiện tại -->
                            <div class="image-gallery">
                                @foreach ($images as $index => $image)
                                    <div class="image-item" data-index="{{ $index }}">
                                        <img src="{{ Storage::url($image) }}" alt="Image" class="img-thumbnail shadow">
                                        <div class="image-actions mt-2">
                                            <label class="form-label">Thay ảnh:</label>
                                            <input type="file" name="updated_images[{{ $index }}]" class="form-control form-control-sm mb-2" accept="image/*">
                                            <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $image }}">
                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-existing-image">
                                                <i class="bi bi-trash"></i> Xóa ảnh này
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold">Thêm ảnh mới</h6>
                            <div id="new-images-container" class="image-gallery mt-3"></div>
                            <button type="button" class="btn btn-success mb-3" id="add-new-image">
                                <i class="bi bi-plus-circle me-1"></i> Thêm ảnh mới
                            </button>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Cập nhật Album
                                </button>
                                <a href="{{ route('backend.product_images.index', ['product_id' => $product->id]) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.image-item {
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
}

.img-thumbnail,
.preview-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}

.image-actions label {
    font-weight: 500;
    margin-bottom: 5px;
    text-align: center;
    width: 100%;
}

.image-actions .form-control-sm {
    font-size: 14px;
    padding: 6px 8px;
}

.btn-remove-new {
    margin-top: 8px;
}

.preview-img {
    display: none;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let deletedImages = [];

    // Xoá ảnh cũ
    document.querySelector('.image-gallery')?.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-existing-image') || e.target.closest('.remove-existing-image')) {
            const item = e.target.closest('.image-item');
            const index = item.getAttribute('data-index');
            deletedImages.push(index);
            document.getElementById('deleted_images').value = JSON.stringify(deletedImages);
            item.remove();
        }
    });

    // Thêm ảnh mới
    document.getElementById('add-new-image').addEventListener('click', function () {
        const container = document.getElementById('new-images-container');

        const item = document.createElement('div');
        item.classList.add('image-item');

        const preview = document.createElement('img');
        preview.classList.add('preview-img', 'shadow-sm');

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'new_images[]';
        input.accept = 'image/*';
        input.classList.add('form-control');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm btn-remove-new';
        removeBtn.innerHTML = '<i class="bi bi-x-circle"></i> Xóa ảnh mới';
        removeBtn.addEventListener('click', function () {
            item.remove();
        });

        item.appendChild(preview);
        item.appendChild(input);
        item.appendChild(removeBtn);

        container.appendChild(item);
    });
});
</script>
