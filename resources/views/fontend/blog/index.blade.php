<main id="content" role="main">
    <style>
        .img-small {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .blog-card {
            transition: all 0.3s ease-in-out;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="bg-gray-13 bg-md-transparent py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bài viết</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                @foreach($posts as $post)
                    <article class="card mb-4 blog-card border-0">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <img class="img-fluid img-small" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h4><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                                    <p class="text-muted mb-2">{{ $post->created_at->format('d/m/Y') }}</p>
                                    <p>{{ Str::limit($post->description, 150) }}</p>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-primary">Đọc tiếp</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="mb-4">
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div class="form-group">
                            <label for="category">Chọn danh mục:</label>
                            <select name="category_post_id" id="category" class="form-control">
                                <option value="">-- Tất cả danh mục --</option>
                                @foreach($category_post as $category)
                                    <option value="{{ $category->id }}" {{ request('category_post_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="search">Tìm kiếm bài viết:</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                    </form>
                </aside>

                <!-- Khám Phá Blog Của Chúng Tôi -->
                <aside class="mb-4">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Khám Phá Blog Của Chúng Tôi</h3>
                    </div>
                    <p class="text-gray-90 mb-0">Chào mừng bạn đến với blog của cửa hàng chúng tôi! Tại đây, bạn sẽ tìm thấy các bài viết chia sẻ thông tin về những sản phẩm máy tính mới nhất, các xu hướng công nghệ hiện đại, cùng những mẹo hay giúp bạn sử dụng máy tính hiệu quả hơn. Chúng tôi cam kết mang đến những kiến thức bổ ích và những sản phẩm chất lượng, giúp bạn có trải nghiệm công nghệ tuyệt vời nhất. Hãy theo dõi blog để luôn cập nhật những thông tin mới nhất nhé!</p>
                </aside>

                <!-- Các danh mục bài viết -->
                <aside class="mb-4">
                    <h3 class="font-size-18 mb-3">Danh Mục Bài Viết</h3>
                    <div class="list-group">
                        @foreach($category_post as $category)
                            <a href="{{ route('blog.index', ['category_post_id' => $category->id]) }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-angle-right mr-2"></i> {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </div>
</main>
