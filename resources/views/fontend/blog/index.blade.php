<main id="content" role="main">
    <!-- breadcrumb -->
    <style>
        .img-small {
    width: 100%; /* Tự động điều chỉnh chiều rộng ảnh theo chiều rộng của container */
    height: auto; /* Giữ tỷ lệ ảnh */
    max-height: 200px; /* Giới hạn chiều cao tối đa của ảnh */
    object-fit: cover; /* Cắt ảnh để phù hợp với container mà không bị méo */
}


    </style>
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('home.index')}}">Trang chủ</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Blog</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-wd">
                <div class="min-width-1100-wd">
                    
                        @foreach($posts as $post)
                        <article class="card mb-13 border-0">
                            <div class="row">
                                <div class="col-lg-4 mb-5 mb-lg-0">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="d-block">
                                        <img class="img-fluid img-small object-fit-cover" 
                                             src="{{ asset('storage/' . $post->image) }}" 
                                             alt="{{ $post->title }}">

                                    </a>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body p-0">
                                        <h4 class="mb-3"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <span class="mx-0dot5 text-gray-5">{{ $post->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <p>{{ Str::limit($post->description, 150) }}</p>
                                        <div class="flex-horizontal-center">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-soft-secondary-w">
                                                Đọc tiếp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                    

                    
                    
                </div>
            </div>
            <div class="col-xl-3 col-wd">
                <aside class="mb-7">
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div class="form-group">
                            <label for="category">Chọn danh mục:</label>
                            <select name="category_id" id="category" class="form-control">
                                <option value="">-- Tất cả danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="search">Tìm kiếm bài viết:</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </form>
                    
                </aside>
                <aside class="mb-7">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Khám Phá Blog Của Chúng Tôi</h3>
                    </div>
                    <p class="text-gray-90 mb-0">Chào mừng bạn đến với blog của cửa hàng chúng tôi! Tại đây, bạn sẽ tìm thấy các bài viết chia sẻ thông tin về những sản phẩm máy tính mới nhất, các xu hướng công nghệ hiện đại, cùng những mẹo hay giúp bạn sử dụng máy tính hiệu quả hơn. Chúng tôi cam kết mang đến những kiến thức bổ ích và những sản phẩm chất lượng, giúp bạn có trải nghiệm công nghệ tuyệt vời nhất. Hãy theo dõi blog để luôn cập nhật những thông tin mới nhất nhé!</p>
                </aside>
                
                
                <aside class="mb-7">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Danh Mục Bài Viết</h3>
                    </div>
                    <div class="list-group">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category_id' => $category->id]) }}" 
                               class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-0">
                                <i class="mr-2 fas fa-angle-right"></i> {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </aside>
               
            </div>
        </div>
       
    </div>
    <div class="d-flex justify-content-center mt-2">
        {{ $posts->links() }}
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->