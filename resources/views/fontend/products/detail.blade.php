<style>
    /* Định dạng mặc định của các ô */
    .attribute-option {
        display: inline-block;
        margin-right: 10px;
        padding: 8px 15px;
        border: 2px solid #ddd;
        /* Viền mặc định */
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: normal;
        /* Giữ font chữ không in đậm mặc định */
    }

    /* Thay đổi viền khi ô được chọn, không thay đổi nội dung */
    .attribute-option input[type="radio"]:checked+.attribute-box {
        background-color: white;
        /* Giữ nền trắng */
        color: black;
        /* Giữ màu chữ không thay đổi */
        border-color: #ffda08;
        /* Màu viền khi chọn */
        font-weight: normal;
        /* Đảm bảo chữ không bị in đậm */
    }

    /* Thêm hiệu ứng hover */
    .attribute-option:hover {
        background-color: #f0f0f0;
    }

    /* Thay đổi viền khi được chọn */
    .attribute-option.selected {
        border-color: yellow;
        /* Viền màu khi được chọn */
    }

    /* Định dạng khi di chuột qua ô */
    .attribute-option:hover {
        border-color: #ebf306;
        /* Viền đổi màu khi hover */
    }

    /* Chỉnh sửa kích thước ảnh chính trong slider */
    #sliderSyncingNav .js-slide img {
        width: 100%;
        height: 400px;
        /* Hoặc chiều cao phù hợp */
        object-fit: cover;
        /* Giúp ảnh đầy khung mà không bị méo */
        border-radius: 8px;
    }

    /* Thumbnails */
    #sliderSyncingThumb .js-slide img {
        width: 100%;
        height: 80px;
        /* Giữ chiều cao cố định cho thumbnail */
        object-fit: cover;
        border-radius: 8px;
    }

    /* Khi nhấn vào ảnh, giữ kích thước đồng nhất */
    #sliderSyncingNav .slick-current img {
        object-fit: contain;
        /* Giữ cho ảnh phóng to không bị méo */
        max-height: 100%;
        /* Đảm bảo ảnh không bị kéo dài quá mức */
    }
</style>

<!-- CSS CMT -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    .rating input {
        display: none;
    }

    .rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }

    .rating input:checked~label {
        color: #ffbb33;
        /* Yellow color for selected stars */
    }

    .rating label:hover,
    .rating label:hover~label {
        color: #ffbb33;
        /* Highlight on hover */
    }

    /* Ensure stars are in left-to-right order */
    .rating {
        display: flex;
        direction: ltr;
        /* Set left-to-right direction */
    }
</style>

<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a
                                href="{{ route('home.index') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            @if ($product->category)
                                <a href="#">{{ $product->category->name }}</a>
                            @else
                                <span>Danh mục không xác định</span>
                            @endif
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">
                            {{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <!-- Single Product Body -->
        <div class="mb-7">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-5 mb-4 mb-md-0">
                    <!-- Slider for main product image -->
                    <div id="sliderSyncingNav" class="js-slick-carousel u-slick mb-2" data-infinite="true"
                        data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                        data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                        data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                        data-nav-for="#sliderSyncingThumb">

                        @foreach ($images as $image)
                            <div class="js-slide">
                                <img class="img-fluid" src="{{ asset('storage/' . $image) }}" alt="Product Image">
                            </div>
                        @endforeach

                    </div>

                    <!-- Thumbnail slider for syncing -->
                    <div id="sliderSyncingThumb"
                        class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--slider-syncing-size u-slick--gutters-1 u-slick--transform-off"
                        data-infinite="true" data-slides-show="5" data-is-thumbs="true"
                        data-nav-for="#sliderSyncingNav">

                        @foreach ($images as $image)
                            <div class="js-slide">
                                <img class="img-fluid" src="{{ asset('storage/' . $image) }}"
                                    alt="Product Image Thumbnail">
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4 mb-md-6 mb-lg-0">
                    <div class="mb-2">
                        <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">
                            {{ $product->category->name ?? 'Danh mục' }}</a>
                        <h2 class="font-size-25 text-lh-1dot2">{{ $product->name }}</h2>
                        <div class="mb-2">
                            <a class="d-inline-flex align-items-center small font-size-15 text-lh-1" href="#">
                                <div class="text-warning mr-2">
                                    <!-- Hiển thị sao dựa trên đánh giá trung bình -->
                                    @for ($i = 1; $i <= 5; $i++)
                                        <small
                                            class="fas fa-star {{ $i <= $averageRating ? '' : 'text-muted' }}"></small>
                                    @endfor
                                </div>
                                <span class="text-secondary font-size-13">({{ $totalReviews }} customer reviews)</span>
                            </a>
                        </div>


                        <div class="d-flex align-items-center">
                            <!-- Ảnh thương hiệu với kích thước nhỏ hơn -->


                            <!-- Tên thương hiệu căn chỉnh chiều cao với ảnh -->
                            @if ($product->brand)
                                <p class=" mb-0 font-weight-bold" style="line-height: 50px;">
                                    Thương hiệu: <a href="#" class="text-dark">{{ $product->brand->name }}</a>
                                </p>
                            @endif
                        </div>
                        <div class="product-main-image mb-3">
                            <img class="img-fluid" src="{{ asset('storage/' . $product->thumbnail) }}"
                                alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        </div>

                        <!-- Thêm thông tin bảo hành và hỗ trợ -->
                        <div class="mb-2">
                            <p class="mb-1 fw-bold">✔ Bảo hành chính hãng 12 tháng.</p>
                            <p class="mb-1 fw-bold">✔ Hỗ trợ đổi mới trong 7 ngày.</p>
                            <p class="mb-1 fw-bold">✔ Windows bản quyền tích hợp.</p>
                        </div>

                        <div class="mb-2">
                            <p>{!! $product->short_description !!}</p>
                        </div>

                        <p><strong>SKU: </strong>{{ $product->sku }}</p>
                    </div>
                </div>
                <div class="mx-md-auto mx-lg-0 col-md-6 col-lg-4 col-xl-3">
                    <div class="mb-2">
                        <div class="card p-5 border-width-2 border-color-1 borders-radius-17">
                            <div class="text-gray-9 font-size-14 pb-2 border-color-1 border-bottom mb-3">Kho :
                                <span id="productStock"
                                    class="{{ $product->quantity > 0 ? 'text-green' : 'text-danger' }} font-weight-bold">
                                    {{ $product->quantity }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <div class="font-size-24" id="productPrice">
                                    @if ($product->price_sale)
                                        <del
                                            class="text-muted">{{ number_format($product->price, 0, ',', '.') }}₫</del>
                                        <span
                                            class="text-danger">{{ number_format($product->price_sale, 0, ',', '.') }}₫</span>
                                    @else
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-size-14">Số lượng</h6>
                                <!-- Quantity -->
                                <div class="border rounded-pill py-1 w-md-60 height-35 px-3 border-color-1">
                                    <div class="js-quantity row align-items-center">
                                        <div class="col">
                                            <input id="quantityInput"
                                                class="js-result form-control h-auto border-0 rounded p-0 shadow-none"
                                                type="number" value="1" min="1"
                                                max="{{ $product->quantity }}">
                                        </div>

                                    </div>
                                </div>
                                <small id="quantityError" class="text-danger d-none">Số lượng không được vượt quá tồn
                                    kho</small>
                                <!-- End Quantity -->
                            </div>

                            <!-- End Quantity -->
                        </div>
                        @php
                            $attributes = [];

                            foreach ($variants as $variant) {
                                foreach ($variant->attributeValues as $attributeValue) {
                                    if (isset($attributeValue->attribute)) {
                                        $attributeName = trim($attributeValue->attribute->name);
                                        $attributeValueText = trim($attributeValue->value);

                                        // Lưu các giá trị vào nhóm thuộc tính
                                        $attributes[$attributeName][$attributeValueText] = $attributeValueText;
                                    }
                                }
                            }
                        @endphp

                        @foreach ($attributes as $attributeName => $values)
                            <div class="mb-3">
                                <h6 class="font-size-14">Chọn {{ ucfirst($attributeName) }}</h6>
                                <div class="attribute-options">
                                    @foreach ($values as $value)
                                        <label class="attribute-option">
                                            <input type="radio"
                                                name="{{ strtolower(string: str_replace(' ', '_', $attributeName)) }}"
                                                value="{{ $value }}" class="d-none">
                                            <span class="attribute-box">{{ $value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach







                        <div class="mb-2 pb-0dot5" style="margin-top: 10px">
                            <a href="{{ route('cart.add') }}" id="addToCartBtn"
                                class="btn btn-block btn-primary-dark" disabled>
                                <i class="ec ec-add-to-cart mr-2 font-size-20"></i>Thêm vào giỏ hàng
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="#" id="buyNowBtn" class="btn btn-block btn-dark" disabled>Mua ngay</a>
                        </div>
                        <div class="flex-content-center flex-wrap">
                            <a href="#" class="text-gray-6 font-size-13 mr-2">
                                <i class="ec ec-favorites mr-1 font-size-15"></i> Yêu thích
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Single Product Body -->
    </div>
    <div class="bg-gray-7 pt-6 pb-3 mb-6">
        <div class="container">
            <div class="js-scroll-nav">

                <div class="bg-white pt-4 pb-6 px-xl-11 px-md-5 px-4  overflow-hidden">
                    {{-- <div id="Description" class="mx-md-2"> --}}
                    <div class="position-relative mb-1">


                        <!-- Tabs -->
                        <ul class="nav nav-classic nav-tab nav-tab-lg justify-content-xl-center mb-6 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble border-lg-down-bottom-0 pb-1 pb-xl-0 mb-n1 mb-xl-0"
                            role="tablist">
                            <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                <a class="nav-link active" data-bs-toggle="tab" href="#Description" role="tab">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        Mô Tả
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                <a class="nav-link" data-bs-toggle="tab" href="#Specification" role="tab">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        Thông Số
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                <a class="nav-link" data-bs-toggle="tab" href="#Reviews" role="tab">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        Đánh Giá
                                    </div>
                                </a>
                            </li>
                        </ul>

                    </div>

                    {{-- </div> --}}
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="Description" role="tabpanel">
                        <div class="mx-md-4 pt-1">

                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10 text-center">

                                    <p>{!! $product->description !!}</p>

                                </div>
                                <div class="col-md-1"></div>


                            </div>
                        </div>
                        <ul class="nav flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                            <li class="nav-item text-gray-111 flex-shrink-0 flex-xl-shrink-1">
                                <strong>SKU:
                                </strong> <span class="sku"> {{ $product->sku }}</span>
                            </li>
                            <li class="nav-item text-gray-111 mx-3 flex-shrink-0 flex-xl-shrink-1">/
                            </li>
                            <li class="nav-item text-gray-111 flex-shrink-0 flex-xl-shrink-1">
                                <strong>Danh Mục:</strong> <a href="#"
                                    class="text-blue">{{ $product->category->name ?? 'Danh mục' }}</a>
                            </li>
                            <li class="nav-item text-gray-111 mx-3 flex-shrink-0 flex-xl-shrink-1">/
                            </li>

                        </ul>
                    </div>

                    <div class="tab-pane fade" id="Specification" role="tabpanel">
                        <div class="mx-md-5 pt-1">
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold px-4 px-xl-5">Thông số</th>
                                            <th class="font-weight-bold">Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->specifications as $specification)
                                            <tr>
                                                <td class="px-4 px-xl-5 {{ $loop->first ? 'border-top-0' : '' }}">
                                                    <strong>{{ $specification->key }}</strong>
                                                </td>
                                                <td class="{{ $loop->last ? 'border-top-0' : '' }}">
                                                    {{ $specification->value }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="Reviews" role="tabpanel">
                        <div class="mb-4 px-lg-4">
                            <div class="row mb-8">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h3 class="font-size-18 mb-6">Dựa trên {{ $totalReviews }} đánh giá</h3>
                                        <h2 class="font-size-30 font-weight-bold text-lh-1 mb-0">{{ $averageRating }}
                                        </h2>
                                        <div class="text-lh-1">Tổng thể</div>
                                    </div>

                                    <!-- Ratings -->
                                    <ul class="list-unstyled">
                                        @for ($i = 5; $i >= 1; $i--)
                                            @php
                                                $ratingCount = $ratingsCount[$i] ?? 0;
                                                $percentage =
                                                    $totalReviews > 0 ? ($ratingCount / $totalReviews) * 100 : 0;
                                            @endphp
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1"
                                                    href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16"
                                                            style="width: 80px;">
                                                            @for ($j = 1; $j <= 5; $j++)
                                                                @if ($j <= $i)
                                                                    <small class="fas fa-star"></small>
                                                                @else
                                                                    <small class="far fa-star text-muted"></small>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5"
                                                            style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $percentage }}%;"
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{ $ratingCount }}</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                    <!-- End Ratings -->
                                </div>


                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="font-size-18 mb-0">
                                            @if ($comment)
                                                <span class="text">Cập nhật đánh giá của bạn</span> <small
                                                    class="text-muted">(Chỉ có thể cập nhật 1 lần)</small>
                                            @else
                                                <span class="text-success">Thêm một đánh giá</span>
                                            @endif
                                        </h3>
                                    </div>


                                    <!-- Form -->
                                    @if ($comment)
                                        <!-- Form chỉnh sửa bình luận -->
                                        <form class="js-validate"
                                            action="{{ route('comments.update', $comment->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT') <!-- Đặt phương thức PUT cho chỉnh sửa -->
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <div class="row align-items-center mb-4">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="rating" class="form-label mb-0">Đánh giá của
                                                        bạn</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <div class="rating">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}"
                                                                name="rating" value="{{ $i }}"
                                                                {{ $comment->rating == $i ? 'checked' : '' }}>
                                                            <label for="star{{ $i }}"
                                                                class="fa fa-star"></label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="js-form-message form-group mb-3 row">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="descriptionTextarea" class="form-label">Bình
                                                        luận</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <textarea class="form-control" rows="3" id="descriptionTextarea" name="content" required>{{ $comment->content }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Thêm trường hình ảnh -->
                                            <div class="js-form-message form-group mb-3 row">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="image" class="form-label">Hình ảnh</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <input type="file" class="form-control" id="image"
                                                        name="image">
                                                    @if ($comment->image)
                                                        <img src="{{ asset('storage/' . $comment->image) }}"
                                                            alt="Image" width="100">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="offset-md-4 offset-lg-3 col-auto">
                                                    <button type="submit" class="btn btn-primary-dark btn-wide">Cập
                                                        nhật bình luận</button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <!-- Form tạo mới bình luận -->
                                        <form class="js-validate" action="{{ route('comments.store') }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <div class="row align-items-center mb-4">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="rating" class="form-label mb-0">Đánh giá của
                                                        bạn</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <div class="rating">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}"
                                                                name="rating" value="{{ $i }}">
                                                            <label for="star{{ $i }}"
                                                                class="fa fa-star"></label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="js-form-message form-group mb-3 row">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="descriptionTextarea" class="form-label">Bình
                                                        luận</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <textarea class="form-control" rows="3" id="descriptionTextarea" name="content" required></textarea>
                                                </div>
                                            </div>

                                            <!-- Thêm trường hình ảnh -->
                                            <div class="js-form-message form-group mb-3 row">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="image" class="form-label">Hình ảnh</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <input type="file" class="form-control" id="image"
                                                        name="image">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="offset-md-4 offset-lg-3 col-auto">
                                                    <button type="submit" class="btn btn-primary-dark btn-wide">Thêm
                                                        bình luận</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>


                            </div>

                            @foreach ($comments as $comment)
                                <!-- Review -->
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-8">
                                        <!-- Giới hạn chiều rộng ở 7 phần trên 12 của grid -->
                                        <div class="border-bottom border-color-1 pb-4 mb-4">
                                            <!-- Review Rating -->
                                            <div
                                                class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                                <div class="text-warning text-ls-n2 font-size-16"
                                                    style="width: 80px;">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $comment->rating)
                                                            <small class="fas fa-star"></small>
                                                        @else
                                                            <small class="far fa-star text-muted"></small>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <!-- End Review Rating -->

                                            <p class="text-gray-90">{{ $comment->content }}</p>

                                            <!-- Display image if exists -->
                                            @if ($comment->image)
                                                <div class="comment-image">
                                                    <img src="{{ Storage::url($comment->image) }}"
                                                        alt="Comment Image" class="img-fluid" />
                                                </div>
                                            @endif

                                            <!-- Reviewer -->
                                            <div class="mb-2">
                                                <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                                                <span class="font-size-13 text-gray-23">
                                                    -
                                                    {{ \Carbon\Carbon::parse($comment->created_at)->locale('vi')->isoFormat('D MMMM YYYY') }}
                                                </span>
                                            </div>
                                            <!-- End Reviewer -->
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4"></div>
                                </div>
                                <!-- End Review -->
                            @endforeach
                            <div class="pagination">
                                {{ $comments->links() }}
                            </div>



                        </div>
                    </div>


                </div>
            </div>


        </div>
    </div>
    </div>
    <div class="container">
        <!-- Related products -->
        <div class="mb-6">
            <div
                class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
                <h3 class="section-title mb-0 pb-2 font-size-22">Sản phẩm liên quan</h3>
            </div>

            <ul class="row list-unstyled products-group no-gutters">
                @foreach ($relatedProducts as $related)
                    <li class="col-6 col-md-3 col-xl-2gdot4-only col-wd-2 product-item">
                        <div class="product-item__outer h-100">
                            <div class="product-item__inner px-xl-4 p-3">
                                <div class="product-item__body pb-xl-2">
                                    <div class="mb-2">
                                        <a href="{{ route('client.products.category', ['slug' => $related->category->slug]) }}"
                                            class="font-size-12 text-gray-5">
                                            {{ $related->category->name }}
                                        </a>

                                    </div>
                                    <h5 class="mb-1 product-item__title">
                                        <a href="{{ route('client.products.detail', $related->slug) }}"
                                            class="text-blue font-weight-bold">
                                            {{ $related->name }}
                                        </a>
                                    </h5>
                                    <div class="mb-2">
                                        <a href="{{ route('client.products.detail', $related->slug) }}"
                                            class="d-block text-center">
                                            <img class="img-fluid w-100" style="height: 150px; object-fit: cover;"
                                                src="{{ asset('storage/' . $related->thumbnail) }}"
                                                alt="{{ $related->name }}">
                                        </a>
                                    </div>
                                    <div class="flex-center-between mb-1">
                                        <div class="prodcut-price">
                                            @if ($related->price_sale)
                                                <div class="prodcut-price d-flex align-items-center position-relative">
                                                    <ins
                                                        class="font-size-20 text-red text-decoration-none">{{ number_format($related->price_sale) }}đ</ins>
                                                    <del
                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($related->price, 0, ',', '.') }}đ</del>
                                                </div>
                                            @else
                                                <div class="text-dark fw-bold fs-5">
                                                    {{ number_format($related->price, 0, ',', '.') }}đ
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-none d-xl-block prodcut-add-cart">
                                            <a href="#" class="btn-add-cart btn-primary transition-3d-hover">
                                                <i class="ec ec-add-to-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-item__footer">
                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                        <a href="#" class="text-gray-6 font-size-13">
                                            <i class="ec ec-favorites mr-1 font-size-15"></i> Wishlist
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- End Related products -->
    </div>

</main>
<!-- ========== END MAIN CONTENT ========== -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let quantityInput = document.getElementById("quantityInput");
        let maxQuantity = parseInt(quantityInput.getAttribute("max"));
        let quantityError = document.getElementById("quantityError");

        // Kiểm tra khi nhập tay vào input
        quantityInput.addEventListener("input", function() {
            let currentValue = parseInt(this.value);
            if (currentValue > maxQuantity) {
                this.value = maxQuantity;
                quantityError.classList.remove("d-none");
            } else {
                quantityError.classList.add("d-none");
            }
        });


    });
    //Tab 
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Lắng nghe sự kiện thay đổi trên các ô radio
        $(".attribute-option input[type='radio']").change(function() {
            // Lưu trữ tất cả các ô đã chọn
            $(".attribute-option").each(function() {
                // Kiểm tra nếu ô radio được chọn
                if ($(this).find('input[type="radio"]').is(":checked")) {
                    $(this).addClass("selected"); // Thêm lớp selected để thay đổi viền
                } else {
                    $(this).removeClass("selected"); // Bỏ lớp selected nếu không chọn
                }
            });
        });
    });
    $(document).ready(function() {
        let variantCache = {}; // Bộ nhớ đệm biến thể

        // Kiểm tra các thuộc tính đã chọn
        function checkVariants() {
            let selectedAttributes = {}; // Lưu biến thể đã chọn

            $(".attribute-option input[type='radio']:checked").each(function() {
                let attributeName = $(this).attr("name");
                let attributeValue = $(this).val();
                selectedAttributes[attributeName] = attributeValue;
            });

            // Kiểm tra nếu chưa chọn đủ thuộc tính
            if (Object.keys(selectedAttributes).length < $(".attribute-options").length) {
                $("#variantAlert").show();
                disablePurchase();
                return false;
            }

            $("#variantAlert").hide();
            return selectedAttributes;
        }

        // Xử lý khi thay đổi thuộc tính (Màu sắc, RAM, v.v.)
        $(".attribute-option input[type='radio']").change(function() {
            let selectedAttributes = checkVariants();
            if (!selectedAttributes) return;

            let cacheKey = JSON.stringify(selectedAttributes);
            let productId = {{ $product->id }}; // ID sản phẩm

            console.log("Selected Attributes:",
                selectedAttributes); // Log để kiểm tra giá trị thuộc tính

            // Kiểm tra bộ nhớ đệm
            if (variantCache[cacheKey]) {
                updateUI(variantCache[cacheKey]);
            } else {
                fetchVariantData(productId, selectedAttributes, cacheKey);
            }
        });

        // Hàm gửi request và cập nhật UI
        function fetchVariantData(productId, selectedAttributes, cacheKey) {
            // Khởi tạo queryParams với product_id
            let queryParams = `product_id=${encodeURIComponent(productId)}`;

            // Duyệt qua các thuộc tính đã chọn và tạo query string
            for (let [key, value] of Object.entries(selectedAttributes)) {
                queryParams += `&${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
            }

            let url = '{{ route('getVariant') }}' + '?' + queryParams;
            console.log("Request URL:", url); // In URL ra console để kiểm tra

            $.ajax({
                url: url, // Sử dụng URL đã mã hóa
                type: 'GET',
                beforeSend: function() {
                    // Bạn có thể cập nhật giá trị giao diện ngay tại đây để không bị hiển thị "Đang tải..."
                    // Ví dụ: Đổi màu viền, hoặc thay đổi nút "Thêm vào giỏ" ngay lập tức.
                    // Cập nhật giao diện ngay lập tức
                    $(".attribute-option").each(function() {
                        $(this).addClass("loading");
                    });
                },
                success: function(response) {
                    console.log("Response from API:", response);
                    if (!response || Object.keys(response).length === 0) {
                        disablePurchase();
                        return;
                    }
                    variantCache[cacheKey] = response;
                    updateUI(response);
                    // Loại bỏ trạng thái loading khi hoàn tất
                    $(".attribute-option").each(function() {
                        $(this).removeClass("loading");
                    });
                },
                error: function(xhr) {
                    console.error("❌ Lỗi AJAX:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: "Có lỗi xảy ra khi tải thông tin biến thể.",
                        confirmButtonText: "OK"
                    });
                    disablePurchase();
                    // Loại bỏ trạng thái loading khi có lỗi
                    $(".attribute-option").each(function() {
                        $(this).removeClass("loading");
                    });
                }
            });
        }

        // Cập nhật giao diện UI
        function updateUI(response) {
            console.log("🏆 UI đang cập nhật...", response); // Debug log

            let quantity = response.quantity ?? 0;

            // Kiểm tra giá
            let price = response.price_sale ?
                `<del class="text-muted">${response.price}</del> 
                <span class="text-danger">${response.price_sale}</span>` :
                `${response.price}`;

            // Cập nhật giá và số lượng
            $("#productPrice").html(price);
            $("#productStock").html(
                `<span class="font-weight-bold ${quantity > 0 ? 'text-green' : 'text-danger'}">${quantity}</span>`
            );

            // Cập nhật số lượng tối đa có thể chọn
            $("#quantityInput").attr("max", quantity);

            // Kiểm tra nếu có hàng, kích hoạt mua
            if (quantity > 0) {
                $("#quantityInput").prop("disabled", false).val(1);
                enablePurchase();
            } else {
                $("#quantityInput").val("").prop("disabled", true);
                disablePurchase();
            }
        }

        // Vô hiệu hóa các nút "Thêm vào giỏ" và "Mua ngay"
        function disablePurchase() {
            $("#addToCartBtn, #buyNowBtn").prop("disabled", true);
        }

        // Kích hoạt các nút nếu đủ điều kiện
        function enablePurchase() {
            let selectedAttributes = checkVariants();
            let quantity = parseInt($("#quantityInput").val(), 10) || 0;
            let stockQuantity = parseInt($("#quantityInput").attr("max"), 10);
            let isVariantProduct = {{ $product->is_variant ? 'true' : 'false' }};

            if (isVariantProduct) {
                // For variant products, we need to check if attributes are selected
                if (!selectedAttributes || quantity < 1 || stockQuantity === 0) {
                    disablePurchase();
                } else {
                    $("#addToCartBtn, #buyNowBtn").prop("disabled", false);
                }
            } else {
                // For non-variant products, just check if quantity is valid
                if (quantity < 1 || stockQuantity === 0) {
                    disablePurchase();
                } else {
                    $("#addToCartBtn, #buyNowBtn").prop("disabled", false);
                }
            }
        }

        // Xử lý thay đổi số lượng
        $("#quantityInput").on("input", function() {
            let max = parseInt($(this).attr("max"), 10);
            let value = $(this).val().replace(/\D/g, "");

            if (max === 0 || value === "") {
                $(this).val("");
                disablePurchase();
                return;
            }

            value = Math.max(1, Math.min(max, parseInt(value, 10)));
            $(this).val(value);
            enablePurchase();
        });

        $("#addToCartBtn, #buyNowBtn").click(function(event) {
            event.preventDefault();

            let selectedAttributes = checkVariants();
            let quantity = parseInt($("#quantityInput").val(), 10) || 1;
            let isVariantProduct = {{ $product->is_variant ? 'true' : 'false' }};

            // For variant products, we need to check if attributes are selected
            if (isVariantProduct && (!selectedAttributes || parseInt($("#quantityInput").attr("max"),
                    10) === 0)) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: "⚠️ Vui lòng chọn đầy đủ biến thể hoặc sản phẩm đã hết hàng!",
                    confirmButtonText: "OK"
                });
                return;
            }

            // For non-variant products, just check if quantity is valid
            if (!isVariantProduct && parseInt($("#quantityInput").attr("max"), 10) === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: "⚠️ Sản phẩm đã hết hàng!",
                    confirmButtonText: "OK"
                });
                return;
            }

            // Prepare the data to send
            let formData = {
                product_id: {{ $product->id }},
                quantity: quantity
            };

            // Only add attributes if this is a variant product
            if (isVariantProduct && selectedAttributes) {
                formData.attributes = selectedAttributes;
            }

            // Determine if this is a buy now or add to cart action
            let isBuyNow = $(this).attr('id') === 'buyNowBtn';
            let url = isBuyNow ? "{{ route('checkout.buy-now') }}" : "{{ route('cart.add') }}";

            // Send POST request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (isBuyNow) {
                        // Redirect to checkout page for buy now
                        window.location.href = "{{ route('checkout.index') }}";
                    } else {
                        // Show success message for add to cart
                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: "Sản phẩm đã được thêm vào giỏ hàng",
                            confirmButtonText: "OK"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: xhr.responseJSON?.message ||
                            "Có lỗi xảy ra, vui lòng thử lại",
                        confirmButtonText: "OK"
                    });
                }
            });
        });

        disablePurchase(); // Đảm bảo các nút bị vô hiệu hóa khi chưa chọn gì
    });
</script>
