<main id="main" class="main">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Chi tiết bình luận</h5>

            <div class="row">
                <!-- Cột ảnh sản phẩm -->
                

                <!-- Cột thông tin bình luận -->
                <div class="col-md-8">
                    <h6><strong>Người dùng:</strong> {{ $comment->user->name }}</h6>
                    <h6><strong>Sản phẩm:</strong> {{ $comment->product->name }}</h6>
                    <p><strong>Nội dung:</strong> {{ $comment->content }}</p>
                    <p><strong>Đánh giá:</strong>
                        <span class="text-warning">
                            @for ($i = 0; $i < $comment->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </span>
                    </p>
                    <p><strong>Trạng thái:</strong>
                        
                            {{ $comment->status ? 'Hiển thị' : 'Ẩn' }}
                        
                    </p>
                    
                    
                    <!-- Nút quay lại danh sách -->
                    <a href="{{ route('comments.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

