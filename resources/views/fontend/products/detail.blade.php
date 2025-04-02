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
        <div class="mb-14">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-5 mb-4 mb-md-0">
                    <div id="sliderSyncingNav" class="js-slick-carousel u-slick mb-2" data-infinite="true"
                        data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                        data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                        data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                        data-nav-for="#sliderSyncingThumb">
                        <div class="js-slide">
                            <img class="img-fluid" src="{{ asset('storage/' . $product->thumbnail) }}"
                                alt="{{ $product->name }}">
                        </div>
                        <div class="js-slide">
                            <img class="img-fluid" src="../../assets/img/720X660/img2.jpg" alt="Image Description">
                        </div>

                    </div>

                    <div id="sliderSyncingThumb"
                        class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--slider-syncing-size u-slick--gutters-1 u-slick--transform-off"
                        data-infinite="true" data-slides-show="5" data-is-thumbs="true"
                        data-nav-for="#sliderSyncingNav">
                        @foreach ($images as $image)
                            @foreach ($image->images as $img)
                                <div class="js-slide">
                                    <img class="img-fluid" src="{{ asset('storage/' . $img) }}" alt="Product Image">
                                </div>
                            @endforeach
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
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="far fa-star text-muted"></small>
                                </div>
                                <span class="text-secondary font-size-13">(3 customer reviews)</span>
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
                        <!-- Thêm thông tin bảo hành và hỗ trợ -->
                        <div class="mb-2">
                            <p class="mb-1 fw-bold">✔ Bảo hành chính hãng 12 tháng.</p>
                            <p class="mb-1 fw-bold">✔ Hỗ trợ đổi mới trong 7 ngày.</p>
                            <p class="mb-1 fw-bold">✔ Windows bản quyền tích hợp.</p>
                        </div>

                        <div class="mb-2">
                            <p>{!! $product->short_description !!}</p>
                        </div>

                        <p><strong>SKU</strong>{{ $product->sku }}</p>
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
                                                name="{{ strtolower(str_replace(' ', '_', $attributeName)) }}"
                                                value="{{ $value }}" class="d-none">
                                            <span class="attribute-box">{{ $value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach







                        <div class="mb-2 pb-0dot5" style="margin-top: 10px">
                            <a href="#" id="addToCartBtn" class="btn btn-block btn-primary-dark" disabled>
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
                                <div class="col-md-12">

                                    <p>{!! $product->description !!}</p>

                                </div>


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
                                        <h3 class="font-size-18 mb-6">Based on 3 reviews</h3>
                                        <h2 class="font-size-30 font-weight-bold text-lh-1 mb-0">4.3
                                        </h2>
                                        <div class="text-lh-1">overall</div>
                                    </div>

                                    <!-- Ratings -->
                                    <ul class="list-unstyled">
                                        <li class="py-1">
                                            <a class="row align-items-center mx-gutters-2 font-size-1"
                                                href="javascript:;">
                                                <div class="col-auto mb-2 mb-md-0">
                                                    <div class="text-warning text-ls-n2 font-size-16"
                                                        style="width: 80px;">
                                                        <small class="fas fa-star"></small>
                                                        <small class="fas fa-star"></small>
                                                        <small class="fas fa-star"></small>
                                                        <small class="fas fa-star"></small>
                                                        <small class="far fa-star text-muted"></small>
                                                    </div>
                                                </div>
                                                <div class="col-auto mb-2 mb-md-0">
                                                    <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: 100%;" aria-valuenow="100"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto text-right">
                                                    <span class="text-gray-90">205</span>
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                    <!-- End Ratings -->
                                </div>
                                <div class="col-md-6">
                                    <h3 class="font-size-18 mb-5">Add a review</h3>
                                    <!-- Form -->
                                    <form class="js-validate">
                                        <div class="row align-items-center mb-4">
                                            <div class="col-md-4 col-lg-3">
                                                <label for="rating" class="form-label mb-0">Your
                                                    Review</label>
                                            </div>
                                            <div class="col-md-8 col-lg-9">
                                                <a href="#" class="d-block">
                                                    <div class="text-warning text-ls-n2 font-size-16">
                                                        <small class="far fa-star text-muted"></small>
                                                        <small class="far fa-star text-muted"></small>
                                                        <small class="far fa-star text-muted"></small>
                                                        <small class="far fa-star text-muted"></small>
                                                        <small class="far fa-star text-muted"></small>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="js-form-message form-group mb-3 row">
                                            <div class="col-md-4 col-lg-3">
                                                <label for="descriptionTextarea" class="form-label">Your
                                                    Review</label>
                                            </div>
                                            <div class="col-md-8 col-lg-9">
                                                <textarea class="form-control" rows="3" id="descriptionTextarea" data-msg="Please enter your message."
                                                    data-error-class="u-has-error" data-success-class="u-has-success"></textarea>
                                            </div>
                                        </div>
                                        <div class="js-form-message form-group mb-3 row">
                                            <div class="col-md-4 col-lg-3">
                                                <label for="inputName" class="form-label">Name <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8 col-lg-9">
                                                <input type="text" class="form-control" name="name"
                                                    id="inputName" aria-label="Alex Hecker" required
                                                    data-msg="Please enter your name." data-error-class="u-has-error"
                                                    data-success-class="u-has-success">
                                            </div>
                                        </div>
                                        <div class="js-form-message form-group mb-3 row">
                                            <div class="col-md-4 col-lg-3">
                                                <label for="emailAddress" class="form-label">Email
                                                    <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8 col-lg-9">
                                                <input type="email" class="form-control" name="emailAddress"
                                                    id="emailAddress" aria-label="alexhecker@pixeel.com" required
                                                    data-msg="Please enter a valid email address."
                                                    data-error-class="u-has-error" data-success-class="u-has-success">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="offset-md-4 offset-lg-3 col-auto">
                                                <button type="submit"
                                                    class="btn btn-primary-dark btn-wide transition-3d-hover">Add
                                                    Review</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- End Form -->
                                </div>
                            </div>
                            <!-- Review -->
                            <div class="border-bottom border-color-1 pb-4 mb-4">
                                <!-- Review Rating -->
                                <div
                                    class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                    <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="far fa-star text-muted"></small>
                                        <small class="far fa-star text-muted"></small>
                                    </div>
                                </div>
                                <!-- End Review Rating -->

                                <p class="text-gray-90">Fusce vitae nibh mi. Integer posuere, libero et
                                    ullamcorper
                                    facilisis, enim eros tincidunt orci, eget vestibulum sapien nisi ut
                                    leo.
                                    Cras
                                    finibus vel est ut mollis. Donec luctus condimentum ante et euismod.
                                </p>

                                <!-- Reviewer -->
                                <div class="mb-2">
                                    <strong>John Doe</strong>
                                    <span class="font-size-13 text-gray-23">- April 3, 2019</span>
                                </div>
                                <!-- End Reviewer -->
                            </div>
                            <!-- End Review -->


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
                                            <img class="img-fluid"
                                                src="{{ asset('storage/' . $related->thumbnail) }}"
                                                alt="{{ $related->name }}">
                                        </a>
                                    </div>
                                    <div class="flex-center-between mb-1">
                                        <div class="prodcut-price">
                                            <div class="text-gray-100">
                                                {{ number_format($related->price, 0, ',', '.') }}đ
                                            </div>
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


//
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

            if (!selectedAttributes || quantity < 1 || stockQuantity === 0) {
                disablePurchase();
            } else {
                $("#addToCartBtn, #buyNowBtn").prop("disabled", false);
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

        // Khi nhấn "Thêm vào giỏ hàng" hoặc "Mua ngay"
        $("#addToCartBtn, #buyNowBtn").click(function(event) {
            event.preventDefault();

            let selectedAttributes = checkVariants();
            let quantity = parseInt($("#quantityInput").val(), 10) || 1;

            if (!selectedAttributes || parseInt($("#quantityInput").attr("max"), 10) === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: "⚠️ Vui lòng chọn đầy đủ biến thể hoặc sản phẩm đã hết hàng!",
                    confirmButtonText: "OK"
                });
                return;
            }

            // Hiển thị thông báo thành công (KHÔNG GỬI REQUEST)
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: "✅ Sản phẩm đã được thêm vào giỏ hàng!",
                confirmButtonText: "OK"
            });
        });

        disablePurchase(); // Đảm bảo các nút bị vô hiệu hóa khi chưa chọn gì
    });
</script>
