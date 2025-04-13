<style>
    /* Tùy chỉnh kiểu nút */
.btn {
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn:hover {
    opacity: 0.85; /* Giảm độ mờ khi hover */
    transform: scale(1.05); /* Phóng to nhẹ khi hover */
}

/* Cải thiện nút hiển thị/ẩn */
.status-toggle-btn {
    background-color: #28a745; /* Màu xanh lá cho "Hiển thị" */
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
}

.status-toggle-btn.warning {
    background-color: #ffc107; /* Màu vàng cho "Ẩn" */
}

.status-toggle-btn:hover {
    background-color: #218838; /* Màu tối hơn khi hover cho "Hiển thị" */
}

.status-toggle-btn.warning:hover {
    background-color: #e0a800; /* Màu tối hơn khi hover cho "Ẩn" */
}

/* Cải thiện nút "Xem chi tiết" */
.btn-info {
    background-color: #17a2b8;
    color: white;
    padding: 10px 20px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
}

.btn-info:hover {
    background-color: #117a8b; /* Màu tối hơn khi hover */
}

.btn-info i {
    margin-right: 8px; /* Tạo khoảng cách giữa icon và text */
}

</style>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý bình luận sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý bình luận</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách bình luận sản phẩm</h5>

                        <!-- Table -->
                        <div class="datatable-container">
                            <table id="comments-table" class="table datatable table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Người dùng</th>
                                        <th class="text-center">Sản phẩm</th>
                                        <th class="text-center">Nội dung</th>
                                        <th class="text-center">Đánh giá</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comments as $comment)
                                        <tr>
                                            <td class="text-center"> <a href="{{ route('products.show', $comment->user->id) }}" >{{ $comment->user->name }}</a></td>
                                            <td class="text-center"><a href="{{ route('products.show', $comment->product->id) }}">{{ $comment->product->name }}</a></td>
                                            <td class="text-center">{{ Str::limit($comment->content, 50) }}</td>
                                            <td class="text-center">{{ $comment->rating }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.comments.toggleStatus', $comment->id) }}" method="POST" class="toggle-status-form" data-comment-id="{{ $comment->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-{{ $comment->status ? 'success' : 'warning' }} status-toggle-btn rounded-pill px-4 py-2">
                                                        {{ $comment->status ? 'Hiển thị' : 'Ẩn' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('comments.show', $comment->id) }}" class="btn btn-info rounded-pill px-4 py-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
