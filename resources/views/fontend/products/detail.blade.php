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
                            $colors = [];
                            $rams = [];

                            foreach ($variants as $variant) {
                                foreach ($variant->attributeValues as $attributeValue) {
                                    if (isset($attributeValue->attribute)) {
                                        $attributeName = trim($attributeValue->attribute->name);
                                        $attributeValueText = trim($attributeValue->value);

                                        if ($attributeName === 'màu sắc') {
                                            $colors[$attributeValueText] = $attributeValueText;
                                        }
                                        if ($attributeName === 'RAM') {
                                            $rams[$attributeValueText] = $attributeValueText;
                                        }
                                    }
                                }
                            }
                        @endphp

                        @if (!empty($colors) || !empty($rams))
                            <div class="mb-3">
                                <h6 class="font-size-14">Chọn màu</h6>
                                <select id="colorSelect"
                                    class="js-select selectpicker dropdown-select btn-block col-12 px-0"
                                    data-style="btn-sm bg-white font-weight-normal py-2 border">
                                    <option value="" selected disabled>Vui lòng chọn màu</option>
                                    <!-- Đổi nội dung -->
                                    @foreach ($colors as $color)
                                        <option value="{{ $color }}">{{ $color }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-size-14">Chọn RAM</h6>
                                <select id="ramSelect"
                                    class="js-select selectpicker dropdown-select btn-block col-12 px-0"
                                    data-style="btn-sm bg-white font-weight-normal py-2 border">
                                    <option value="" selected disabled>Vui lòng chọn RAM</option>
                                    <!-- Đổi nội dung -->
                                    @foreach ($rams as $ram)
                                        <option value="{{ $ram }}">{{ $ram }}</option>
                                    @endforeach
                                </select>
                            </div>


                        @endif

                        <div class="mb-2 pb-0dot5" style="margin-top: 10px">
                            <a href="#" id="addToCartBtn" class="btn btn-block btn-primary-dark">
                                <i class="ec ec-add-to-cart mr-2 font-size-20"></i>Thêm vào giỏ hàng
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="#" id="buyNowBtn" class="btn btn-block btn-dark">Mua ngay</a>
                        </div>
                        <div class="flex-content-center flex-wrap">
                            <a href="#" class="text-gray-6 font-size-13 mr-2"><i
                                    class="ec ec-favorites mr-1 font-size-15"></i> Yêu thích</a>

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

                <div class="bg-white pt-4 pb-6 px-xl-11 px-md-5 px-4 mb-6 overflow-hidden">
                    <div id="Description" class="mx-md-2">
                        <div class="position-relative mb-6">
                            <!-- Tabs -->
                            <ul class="nav nav-classic nav-tab nav-tab-lg justify-content-xl-center mb-6 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble border-lg-down-bottom-0 pb-1 pb-xl-0 mb-n1 mb-xl-0"
                                role="tablist">
                                <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                    <a class="nav-link active" data-toggle="tab" href="#Description" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            Description
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                    <a class="nav-link" data-toggle="tab" href="#Specification" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            Specification
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                    <a class="nav-link" data-toggle="tab" href="#Reviews" role="tab">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            Reviews
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <!-- Nội dung Tab -->
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="Description" role="tabpanel">
                                    <div class="mx-md-4 pt-1">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pt-lg-8 pt-xl-10">
                                                    <p>{!! $product->description !!}</p>
                                                </div>
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
                                            <strong>Category:</strong> <a href="#"
                                                class="text-blue">{{ $product->category->name ?? 'Danh mục' }}</a>
                                        </li>
                                        <li class="nav-item text-gray-111 mx-3 flex-shrink-0 flex-xl-shrink-1">/
                                        </li>

                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="Specification" role="tabpanel">
                                    <div class="mx-md-5 pt-1">
                                        <div class="table-responsive mb-4">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th class="px-4 px-xl-5 border-top-0">Weight</th>
                                                        <td class="border-top-0">7.25kg</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Dimensions</th>
                                                        <td>90 x 60 x 90 cm</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Size</th>
                                                        <td>One Size Fits all</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">color</th>
                                                        <td>Black with Red, White with Gold</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Guarantee</th>
                                                        <td>5 years</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <h3 class="font-size-18 mb-4">Technical Specifications</h3>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th class="px-4 px-xl-5 border-top-0">Brand</th>
                                                        <td class="border-top-0">Apple</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Item Height</th>
                                                        <td>18 Millimeters</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Item Width</th>
                                                        <td>31.4 Centimeters</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Screen Size</th>
                                                        <td>13 Inches</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Item Weight</th>
                                                        <td>1.6 Kg</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Product Dimensions</th>
                                                        <td>21.9 x 31.4 x 1.8 cm</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Item model number</th>
                                                        <td>MF841HN/A</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Processor Brand</th>
                                                        <td>Intel</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Processor Type</th>
                                                        <td>Core i5</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Processor Speed</th>
                                                        <td>2.9 GHz</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">RAM Size</th>
                                                        <td>8 GB</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Hard Drive Size</th>
                                                        <td>512 GB</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Hard Disk Technology</th>
                                                        <td>Solid State Drive</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Graphics Coprocessor</th>
                                                        <td>Intel Integrated Graphics</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Graphics Card Description</th>
                                                        <td>Integrated Graphics Card</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Hardware Platform</th>
                                                        <td>Mac</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Operating System</th>
                                                        <td>Mac OS</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="px-4 px-xl-5">Average Battery Life (in hours)</th>
                                                        <td>9</td>
                                                    </tr>
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
                                                    <h2 class="font-size-30 font-weight-bold text-lh-1 mb-0">4.3</h2>
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
                                                                <div class="progress ml-xl-5"
                                                                    style="height: 10px; width: 200px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: 100%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-right">
                                                                <span class="text-gray-90">205</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="py-1">
                                                        <a class="row align-items-center mx-gutters-2 font-size-1"
                                                            href="javascript:;">
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="text-warning text-ls-n2 font-size-16"
                                                                    style="width: 80px;">
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="progress ml-xl-5"
                                                                    style="height: 10px; width: 200px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: 53%;" aria-valuenow="53"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-right">
                                                                <span class="text-gray-90">55</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="py-1">
                                                        <a class="row align-items-center mx-gutters-2 font-size-1"
                                                            href="javascript:;">
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="text-warning text-ls-n2 font-size-16"
                                                                    style="width: 80px;">
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="progress ml-xl-5"
                                                                    style="height: 10px; width: 200px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: 20%;" aria-valuenow="20"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-right">
                                                                <span class="text-gray-90">23</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="py-1">
                                                        <a class="row align-items-center mx-gutters-2 font-size-1"
                                                            href="javascript:;">
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="text-warning text-ls-n2 font-size-16"
                                                                    style="width: 80px;">
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="progress ml-xl-5"
                                                                    style="height: 10px; width: 200px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: 0%;" aria-valuenow="0"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-right">
                                                                <span class="text-muted">0</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="py-1">
                                                        <a class="row align-items-center mx-gutters-2 font-size-1"
                                                            href="javascript:;">
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="text-warning text-ls-n2 font-size-16"
                                                                    style="width: 80px;">
                                                                    <small class="fas fa-star"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                    <small class="far fa-star text-muted"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto mb-2 mb-md-0">
                                                                <div class="progress ml-xl-5"
                                                                    style="height: 10px; width: 200px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: 1%;" aria-valuenow="1"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto text-right">
                                                                <span class="text-gray-90">4</span>
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
                                                                data-msg="Please enter your name."
                                                                data-error-class="u-has-error"
                                                                data-success-class="u-has-success">
                                                        </div>
                                                    </div>
                                                    <div class="js-form-message form-group mb-3 row">
                                                        <div class="col-md-4 col-lg-3">
                                                            <label for="emailAddress" class="form-label">Email <span
                                                                    class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-md-8 col-lg-9">
                                                            <input type="email" class="form-control"
                                                                name="emailAddress" id="emailAddress"
                                                                aria-label="alexhecker@pixeel.com" required
                                                                data-msg="Please enter a valid email address."
                                                                data-error-class="u-has-error"
                                                                data-success-class="u-has-success">
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
                                                <div class="text-warning text-ls-n2 font-size-16"
                                                    style="width: 80px;">
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
                                                facilisis, enim eros tincidunt orci, eget vestibulum sapien nisi ut leo.
                                                Cras
                                                finibus vel est ut mollis. Donec luctus condimentum ante et euismod.</p>

                                            <!-- Reviewer -->
                                            <div class="mb-2">
                                                <strong>John Doe</strong>
                                                <span class="font-size-13 text-gray-23">- April 3, 2019</span>
                                            </div>
                                            <!-- End Reviewer -->
                                        </div>
                                        <!-- End Review -->
                                        <!-- Review -->
                                        <div class="border-bottom border-color-1 pb-4 mb-4">
                                            <!-- Review Rating -->
                                            <div
                                                class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                                <div class="text-warning text-ls-n2 font-size-16"
                                                    style="width: 80px;">
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                </div>
                                            </div>
                                            <!-- End Review Rating -->

                                            <p class="text-gray-90">Pellentesque habitant morbi tristique senectus et
                                                netus et
                                                malesuada fames ac turpis egestas. Suspendisse eget facilisis odio. Duis
                                                sodales
                                                augue eu tincidunt faucibus. Etiam justo ligula, placerat ac augue id,
                                                volutpat
                                                porta dui.</p>

                                            <!-- Reviewer -->
                                            <div class="mb-2">
                                                <strong>Anna Kowalsky</strong>
                                                <span class="font-size-13 text-gray-23">- April 3, 2019</span>
                                            </div>
                                            <!-- End Reviewer -->
                                        </div>
                                        <!-- End Review -->
                                        <!-- Review -->
                                        <div class="pb-4 mb-4">
                                            <!-- Review Rating -->
                                            <div
                                                class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                                <div class="text-warning text-ls-n2 font-size-16"
                                                    style="width: 80px;">
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="fas fa-star"></small>
                                                    <small class="far fa-star text-muted"></small>
                                                </div>
                                            </div>
                                            <!-- End Review Rating -->

                                            <p class="text-gray-90">Sed id tincidunt sapien. Pellentesque cursus
                                                accumsan tellus,
                                                nec ultricies nulla sollicitudin eget. Donec feugiat orci vestibulum
                                                porttitor
                                                sagittis.</p>

                                            <!-- Reviewer -->
                                            <div class="mb-2">
                                                <strong>Peter Wargner</strong>
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


        </div>
    </div>
    </div>
    <div class="container">
        <!-- Related products -->
        <div class="mb-6">
            <div
                class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
                <h3 class="section-title mb-0 pb-2 font-size-22">Related Products</h3>
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
                                                {{ number_format($related->price, 0, ',', '.') }}đ</div>
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
        <!-- End Related products -->

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
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let variantCache = {}; // Bộ nhớ đệm để lưu trữ kết quả biến thể

        $('#colorSelect, #ramSelect').change(function() {
            let color = $('#colorSelect').val();
            let ram = $('#ramSelect').val();

            console.log("🎨 Chọn màu:", color, "💾 Chọn RAM:", ram);

            if (color && ram) {
                let cacheKey = color + "_" + ram;

                if (variantCache[cacheKey]) {
                    console.log("⚡ Dữ liệu lấy từ cache:", variantCache[cacheKey]);
                    updateUI(variantCache[cacheKey]); // Cập nhật giao diện ngay
                    return;
                }

                $.ajax({
                    url: '{{ route('getVariant') }}',
                    type: 'GET',
                    data: {
                        product_id: {{ $product->id }},
                        color: color,
                        ram: ram
                    },
                    beforeSend: function() {
                        console.log("⏳ Gửi request đến server...");
                        $('#productPrice').html(
                            '<span class="text-muted">Đang tải...</span>');
                    },
                    success: function(response) {
                        console.log("✅ Phản hồi từ server:", response);

                        if (!response || Object.keys(response).length === 0) {
                            console.warn("⚠️ Không có dữ liệu biến thể!");
                            return;
                        }

                        variantCache[cacheKey] = response; // Lưu vào cache
                        updateUI(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("❌ Lỗi AJAX:", error);
                        console.error("📌 Chi tiết lỗi:", xhr.responseText);
                    }
                });
            }
        });

        function updateUI(response) {
            $('#productPrice').html(
                response.price_sale ?
                `<del class="text-muted">${response.price}</del> 
     <span class="text-danger">${response.price_sale}</span>` :
                `${response.price}`
            );

            let quantity = response.quantity ?? 0;
            let quantityInput = $('#quantityInput');

            // Cập nhật giá trị `max`
            quantityInput.attr('max', quantity);

            // Nếu số lượng đang nhập lớn hơn max mới, đặt lại bằng max
            if (parseInt(quantityInput.val()) > quantity) {
                quantityInput.val(quantity);
            }

            $('#productStock').html(quantity > 0 ?
                `<span class="text-green font-weight-bold">${quantity}</span> sản phẩm` :
                `<span class="text-danger font-weight-bold">Hết hàng</span>`
            );

            // Nếu hết hàng, disable nút mua hàng
            $('#addToCartBtn, #buyNowBtn').prop('disabled', quantity <= 0);
            $('#quantityError').addClass('d-none'); // Ẩn lỗi nếu có
        }

        // Kiểm tra số lượng nhập tay
        $('#quantityInput').on('input', function() {
            let max = parseInt($(this).attr('max'), 10);
            let min = parseInt($(this).attr('min'), 10);
            let value = $(this).val();

            if (value === "") return; // Cho phép xóa tạm thời

            value = parseInt(value, 10);

            if (isNaN(value) || value < min) {
                $(this).val(min);
                $('#quantityError').addClass('d-none');
            } else if (value > max) {
                $(this).val(max);
                $('#quantityError').removeClass('d-none');
            } else {
                $('#quantityError').addClass('d-none');
            }
        });

        // Khi mất focus, nếu rỗng thì đặt về min
        $('#quantityInput').on('blur', function() {
            if ($(this).val() === "") {
                $(this).val($(this).attr('min'));
            }
        });


        function validateSelection(showAlert = false) {
            let colorSelect = $('#colorSelect');
            let ramSelect = $('#ramSelect');
            let color = colorSelect.val();
            let ram = ramSelect.val();
            let quantity = parseInt($('#quantityInput').attr('max')) || 0;

            let hasVariants = (colorSelect.length > 0 && colorSelect.find('option').length > 1) ||
                (ramSelect.length > 0 && ramSelect.find('option').length > 1);

            // Nếu sản phẩm không có biến thể, chỉ kiểm tra tồn kho
            if (!hasVariants) {
                if (quantity <= 0) {
                    if (showAlert) alert("⚠️ Sản phẩm đã hết hàng! Không thể mua.");
                    return false;
                }
                return true;
            }

            // Kiểm tra nếu sản phẩm có biến thể nhưng chưa chọn đủ
            if (colorSelect.length > 0 && !color) {
                if (showAlert) alert("⚠️ Vui lòng chọn màu sắc trước khi tiếp tục!");
                return false;
            }
            if (ramSelect.length > 0 && !ram) {
                if (showAlert) alert("⚠️ Vui lòng chọn bộ nhớ RAM trước khi tiếp tục!");
                return false;
            }
            if (quantity <= 0) {
                if (showAlert) alert("⚠️ Sản phẩm đã hết hàng! Không thể mua.");
                return false;
            }

            return true;
        }

        function updateButtonState() {
            if (validateSelection(false)) {
                $('#addToCartBtn, #buyNowBtn').prop('disabled', false);
            } else {
                $('#addToCartBtn, #buyNowBtn').prop('disabled', true);
            }
        }

        $('#addToCartBtn, #buyNowBtn').click(function(event) {
            if (!validateSelection(true)) {
                event.preventDefault();
            } else {
                console.log("✅ Điều kiện hợp lệ, tiếp tục...");
            }
        });

        // Kiểm tra trạng thái ngay từ đầu
        updateButtonState();
    });
</script>
