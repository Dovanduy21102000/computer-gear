<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chỉnh sửa Album Ảnh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backend.album.index') }}">Album Ảnh</a>
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
                        <h5 class="card-title">Chỉnh sửa ảnh trong Album</h5>

                        <form action="{{ route('backend.album.update', $image->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="album_id" value="{{ $image->id }}">

                            <div id="images-container">
                                @foreach ($images as $index => $img)
                                    <div class="image-row d-flex gap-2 mb-3" data-id="{{ $img->id }}">
                                        <input type="hidden" name="images[{{ $index }}][id]" value="{{ $img->id }}">
                                        <input type="file" class="form-control" name="images[{{ $index }}][image]" accept="image/*">
                                        <img src="{{ Storage::url($img->image) }}" alt="Image preview" class="img-thumbnail" style="max-width: 100px;">
                                        <button type="button" class="btn btn-danger remove-image">Xóa</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-success" id="add-image">Thêm ảnh</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('backend.album.index') }}" class="btn btn-secondary">Quay lại</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let imageIndex = {{ count($images) }}; // Chỉ mục bắt đầu từ số ảnh hiện có
    let deletedImages = [];

    // Xử lý thêm ảnh mới
    document.getElementById("add-image").addEventListener("click", function() {
        let container = document.getElementById("images-container");

        let newImage = document.createElement("div");
        newImage.classList.add("image-row", "d-flex", "gap-2", "mb-3");
        newImage.innerHTML = `
            <input type="hidden" name="images[${imageIndex}][id]" value="">
            <input type="file" class="form-control" name="images[${imageIndex}][image]" accept="image/*" required>
            <button type="button" class="btn btn-danger remove-image">Xóa</button>
        `;

        container.appendChild(newImage);
        imageIndex++;
    });

    // Xử lý xóa ảnh khi ấn "X"
    document.getElementById("images-container").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-image")) {
            let row = event.target.closest(".image-row");

            if (!row) return; // Nếu không tìm thấy hàng thì thoát

            let imageId = row.getAttribute("data-id");

            if (imageId) {
                deletedImages.push(imageId);
                document.getElementById("deleted_images").value = JSON.stringify(deletedImages);
            }

            row.remove(); // Ẩn hàng bị xóa
        }
    });
});
</script>
