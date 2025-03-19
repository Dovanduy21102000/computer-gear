<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('home.index')}}">Home</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{route('blog.index')}}">Blog</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="#">BlogShow</a></li>
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
                    <article class="card mb-8 border-0">
                        <!-- Hiển thị ảnh bài viết -->
                        <img class="img-fluid" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" style="width: 1000px; height: 500px;">
                       <br>
            
                        <div class="card-body pt-5 pb-0 px-0">
                            <div class="d-block d-md-flex flex-center-between mb-4 mb-md-0">
                                <!-- Hiển thị tiêu đề bài viết -->
                                <h4 class="mb-md-3 mb-1">{{ $post->title }}</h4>
                                <a href="#" class="font-size-12 text-gray-5 ml-md-4">
                                    <i class="far fa-comment"></i> Leave a comment
                                </a>
                            </div>
            
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="list-group list-group-horizontal flex-wrap list-group-borderless align-items-center mx-n0dot5">
                                    <!-- Hiển thị thể loại bài viết (category) -->
                                    {{-- @foreach($post->categories as $category)
                                        <a href="#" class="mx-0dot5 text-gray-5">{{ $category->name }}</a>
                                    @endforeach --}}
                                    <span class="mx-2 font-size-n5 mt-1 text-gray-5"><i class="fas fa-circle"></i></span>
                                    <!-- Hiển thị ngày đăng -->
                                    <a href="#" class="mx-0dot5 text-gray-5">{{ $post->created_at->format('F j, Y') }}</a>
                                </div>
                            </div>
            
                            <!-- Hiển thị mô tả bài viết -->
                            <p><strong>{{ $post->description }}</strong></p>
                            <!-- Hiển thị nội dung bài viết -->
                            <p>{!! $post->content !!}</p>
                            
                            
                        </div>
                    </article>
            
                    <ul class="nav justify-content-between mb-11">
                        <!-- Hiển thị bài viết trước và sau -->
                        @if($recentPosts->count() > 0)
                            <li class="nav-item m-0">
                                <a class="nav-link text-gray-27 px-0" href="{{ route('blog.show', ['slug' => $recentPosts[0]->slug]) }}">
                                    <span class="mr-1">←</span> {{ $recentPosts[0]->title }}
                                </a>
                            </li>
                            @if($recentPosts->count() > 1)
                                <li class="nav-item m-0">
                                    <a class="nav-link text-gray-27 px-0" href="{{ route('blog.show', ['slug' => $recentPosts[1]->slug]) }}">
                                        {{ $recentPosts[1]->title }} <span class="ml-1">→</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="col-xl-3 col-wd">
                <aside class="mb-7">
                    <form class="">
                        <div class="d-flex align-items-center">
                            <label class="sr-only" for="signupSrEmail">Search Electro blog</label>
                            <div class="input-group">
                                <input type="text" class="form-control px-4" name="search" id="signupSrEmail" placeholder="Search..." aria-label="Search Electro blog">
                            </div>
                            <button type="submit" class="btn btn-primary text-nowrap ml-3 d-none">
                                <span class="fas fa-search font-size-1 mr-2"></span> Search
                            </button>
                        </div>
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
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Categories</h3>
                    </div>
                    <div class="list-group">
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-0"><i class="mr-2 fas fa-angle-right"></i> Design</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Events</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Links & Quotes</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> News</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Social</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Technology</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Audios</a>
                        <a href="../blog/single-blog-post.html" class="font-bold-on-hover px-3 py-2 list-group-item list-group-item-action border-right-0 border-left-0 border-bottom-0"><i class="mr-2 fas fa-angle-right"></i> Videos</a>
                    </div>
                </aside>
               
            </div>
        </div>
        
    </div>
</main>