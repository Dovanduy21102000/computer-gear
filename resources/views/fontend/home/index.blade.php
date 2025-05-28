<main id="content" role="main">
    <!-- Slider Section -->

    <div class="mb-4 mt-1">
        <div class="container overflow-hidden">
            <div class="js-slick-carousel u-slick" data-autoplay="true" data-speed="3000"
                data-pagi-classes="text-center position-absolute right-0 bottom-0 left-0 u-slick__pagination u-slick__pagination--long justify-content-start mb-3 mb-md-4 offset-xl-2 pl-xl-16 pl-wd-13">
                @foreach ($banners as $banner)
                    <div class="js-slide">
                        <div class="row pt-7 py-md-0">
                            <div class="col-12">
                                <div class="banner-container">
                                    <img class="banner-img" src="{{ asset('storage/' . $banner->image) }}"
                                        alt="Banner Image">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <style>
        .banner-container {
            width: 100%;
            height: 500px;
            /* Điều chỉnh chiều cao tại đây */
            overflow: hidden;
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Đảm bảo ảnh không bị méo, vẫn full khung */
        }

        .truncate-title,
        .truncate-title a {
            display: block !important;
            max-width: 140px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
    </style>
    <!-- End Slider Section -->

    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-wd-auto d-none d-xl-block">
                <div class="max-width-270 min-width-270 pt-xl-13 mt-xl-13">

                    <!-- Latest Products -->
                    <aside class="mb-4">
                        <!-- Wrapper Latest Products -->
                        <div class="mb-2 position-relative home-new-products-list" style="position: relative;">
                            <div id="homeProductListSpinner"
                                style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <dv
                                class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                                <h3 class="section-title section-title__sm mb-0 pb-3 font-size-18">Sản phẩm mới nhất
                                </h3>
                            </dv>
                            <div class="js-slick-carousel u-slick u-slick--gutters-2 overflow-hidden u-slick-overflow-visble pt-3 position-static"
                                data-slides-show="1" data-slides-scroll="1"
                                data-arrows-classes="position-absolute top-0 font-size-17 u-slick__arrow-normal top-10"
                                data-arrow-left-classes="fa fa-angle-left right-1"
                                data-arrow-right-classes="fa fa-angle-right right-0">
                                <div class="js-slide">
                                    <ul class="list-unstyled products-group mb-0 overflow-visible">
                                        @foreach ($newProducts as $key => $newProduct)
                                            @if ($key < 8)
                                                <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0"
                                                    data-product-id="{{ $newProduct->id }}">
                                                    <div class="product-item__outer h-100">
                                                        <div
                                                            class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                            <div class="col-auto product-media-left">
                                                                <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                    class="d-block"><img class="img-fluid"
                                                                        src="{{ asset('storage/' . $newProduct->thumbnail) }}"
                                                                        alt="Image Description"
                                                                        style="width: 70px !important; height: 70px !important; object-fit: cover;">
                                                            </div>
                                                            <div class="col product-item__body pl-2 pl-lg-3">
                                                                <div class="">
                                                                    <h5 class="product-item__title truncate-title">
                                                                        <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                            class="text-gray-90">{{ Str::limit($newProduct->name, 22) }}</a>
                                                                    </h5>
                                                                </div>
                                                                <div class="text-warning text-ls-n2 font-size-16"
                                                                    style="width: 80px;">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <small
                                                                            class="{{ $i <= $newProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $newProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                                    @endfor
                                                                </div>
                                                                <div class="flex-center-between product-price">
                                                                    <div class="prodcut-price mt-3">
                                                                        @if ($newProduct->is_variant && $newProduct->variants->count())
                                                                            @php
                                                                                $prices = $newProduct->variants->pluck(
                                                                                    'price',
                                                                                );
                                                                                $salePrices = $newProduct->variants
                                                                                    ->pluck('price_sale')
                                                                                    ->filter();
                                                                                $minPrice = $salePrices->count()
                                                                                    ? $salePrices->min()
                                                                                    : $prices->min();
                                                                                $originalMin = $prices->min();
                                                                            @endphp
                                                                            @if ($salePrices->count())
                                                                                <div
                                                                                    class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                    <ins
                                                                                        class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                                    <del
                                                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                                </div>
                                                                            @else
                                                                                <span
                                                                                    class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                            @endif
                                                                        @elseif ($newProduct->price_sale && $newProduct->price_sale > 0)
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($newProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($newProduct->price, 0, ',', '.') }}đ</del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($newProduct->price, 0, ',', '.') }}đ</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="js-slide">
                                    <ul class="list-unstyled products-group mb-0 overflow-visible">
                                        @foreach ($newProducts as $key => $newProduct)
                                            @if ($key >= 8 && $key < 16)
                                                <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0"
                                                    data-product-id="{{ $newProduct->id }}">
                                                    <div class="product-item__outer h-100">
                                                        <div
                                                            class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                            <div class="col-auto product-media-left">
                                                                <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                    class="max-width-70 d-block"><img class="img-fluid"
                                                                        src="{{ asset('storage/' . $newProduct->thumbnail) }}"
                                                                        alt="Image Description"
                                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                            </div>
                                                            <div class="col product-item__body pl-2 pl-lg-3">
                                                                <div class="mb-4">
                                                                    <h5 class="product-item__title truncate-title">
                                                                        <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                            class="text-gray-90">{{ Str::limit($newProduct->name, 22) }}</a>
                                                                    </h5>
                                                                </div>
                                                                <div class="flex-center-between product-price">
                                                                    <div class="prodcut-price mt-3">
                                                                        @if ($newProduct->is_variant && $newProduct->variants->count())
                                                                            @php
                                                                                $prices = $newProduct->variants->pluck(
                                                                                    'price',
                                                                                );
                                                                                $salePrices = $newProduct->variants
                                                                                    ->pluck('price_sale')
                                                                                    ->filter();
                                                                                $minPrice = $salePrices->count()
                                                                                    ? $salePrices->min()
                                                                                    : $prices->min();
                                                                                $originalMin = $prices->min();
                                                                            @endphp
                                                                            @if ($salePrices->count())
                                                                                <div
                                                                                    class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                    <ins
                                                                                        class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                                    <del
                                                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                                </div>
                                                                            @else
                                                                                <span
                                                                                    class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                            @endif
                                                                        @elseif ($newProduct->price_sale && $newProduct->price_sale > 0)
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($newProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($newProduct->price, 0, ',', '.') }}đ</del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($newProduct->price, 0, ',', '.') }}đ</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="js-slide">
                                    <ul class="list-unstyled products-group mb-0 overflow-visible">
                                        @foreach ($newProducts as $key => $newProduct)
                                            @if ($key >= 16 && $key < 24)
                                                <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0"
                                                    data-product-id="{{ $newProduct->id }}">
                                                    <div class="product-item__outer h-100">
                                                        <div
                                                            class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                            <div class="col-auto product-media-left">
                                                                <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                    class="max-width-70 d-block"><img
                                                                        class="img-fluid"
                                                                        src="{{ asset('storage/' . $newProduct->thumbnail) }}"
                                                                        alt="Image Description"
                                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                            </div>
                                                            <div class="col product-item__body pl-2 pl-lg-3">
                                                                <div class="mb-4">
                                                                    <h5 class="product-item__title truncate-title">
                                                                        <a href="{{ route('client.products.detail', $newProduct->slug) }}"
                                                                            class="text-gray-90">{{ Str::limit($newProduct->name, 22) }}</a>
                                                                    </h5>
                                                                </div>
                                                                <div class="flex-center-between product-price">
                                                                    <div class="prodcut-price mt-3">
                                                                        @if ($newProduct->is_variant && $newProduct->variants->count())
                                                                            @php
                                                                                $prices = $newProduct->variants->pluck(
                                                                                    'price',
                                                                                );
                                                                                $salePrices = $newProduct->variants
                                                                                    ->pluck('price_sale')
                                                                                    ->filter();
                                                                                $minPrice = $salePrices->count()
                                                                                    ? $salePrices->min()
                                                                                    : $prices->min();
                                                                                $originalMin = $prices->min();
                                                                            @endphp
                                                                            @if ($salePrices->count())
                                                                                <div
                                                                                    class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                    <ins
                                                                                        class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                                    <del
                                                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                                </div>
                                                                            @else
                                                                                <span
                                                                                    class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                            @endif
                                                                        @elseif ($newProduct->price_sale && $newProduct->price_sale > 0)
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($newProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($newProduct->price, 0, ',', '.') }}đ</del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($newProduct->price, 0, ',', '.') }}đ</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Wrapper Latest Products -->
                    </aside>
                    <!-- End Latest Products -->
                    <!-- Feature List -->
                    <aside class="mb-8">
                        <div class="d-flex justify-content-center rounded border mb-4">
                            <div class="px-4 py-6 w-100">
                                <!-- Danh sách tính năng -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-transport font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Giao hàng miễn phí</span>
                                        <div class="text-secondary">từ 0đ</div>
                                    </div>
                                </div>
                                <!-- End Danh sách tính năng -->

                                <!-- Danh sách tính năng -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-customers font-size-56"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">99% Khách hàng</span>
                                        <div class="text-secondary">Phản hồi</div>
                                    </div>
                                </div>
                                <!-- End Danh sách tính năng -->

                                <!-- Danh sách tính năng -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-returning font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">30 Ngày</span>
                                        <div class="text-secondary">đổi trả miễn phí</div>
                                    </div>
                                </div>
                                <!-- End Danh sách tính năng -->

                                <!-- Danh sách tính năng -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-payment font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Thanh toán</span>
                                        <div class="text-secondary">Hệ thống bảo mật</div>
                                    </div>
                                </div>
                                <!-- End Danh sách tính năng -->

                                <!-- Danh sách tính năng -->
                                <div class="media px-3" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-tag font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Chỉ có</span>
                                        <div class="text-secondary">Các thương hiệu tốt nhất</div>
                                    </div>
                                </div>
                                <!-- End Danh sách tính năng -->
                            </div>
                        </div>
                    </aside>

                    <!-- End Feature List -->
                    <!-- Feature Product -->
                    <aside class="mb-8">
                        <!-- Featured Products -->
                        <div class="position-relative">
                            <div class="border-bottom border-color-1 mb-2">
                                <h3 class="section-title mb-0 pb-3 font-size-18">Sản phẩm nổi bật</h3>
                            </div>
                            <div class="mb-8 position-relative home-featured-products-list"
                                style="position: relative;">
                                <div id="homeFeaturedProductListSpinner"
                                    style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="js-slick-carousel u-slick position-static overflow-hidden u-slick-overflow-visble"
                                    data-slides-show="1" data-slides-scroll="1"
                                    data-arrows-classes="position-absolute top-0 font-size-17 u-slick__arrow-normal top-10"
                                    data-arrow-left-classes="fa fa-angle-left right-1"
                                    data-arrow-right-classes="fa fa-angle-right right-0">
                                    @foreach ($topViewedProducts as $topViewedProduct)
                                        <div class="js-slide products-group">
                                            <div class="product-item remove-divider">
                                                <div class="product-item__outer h-100">
                                                    <div
                                                        class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                        <div class="product-item__body pb-xl-2">
                                                            <div class="mb-2">
                                                                <a href="{{ route('client.products.detail', $topViewedProduct->slug) }}"
                                                                    class="d-block text-center">
                                                                    <img class="img-fluid"
                                                                        style="height: 150px; width: 150px; object-fit: cover;"
                                                                        src="{{ asset('storage/' . $topViewedProduct->thumbnail) }}"
                                                                        alt="Image Description">

                                                                </a>

                                                            </div>
                                                            <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                                style="width: 80px;">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <small
                                                                        class="{{ $i <= $topViewedProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $topViewedProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                                @endfor
                                                            </div>
                                                            <div class="mb-2"><a
                                                                    href="{{ route('client.products.brand', ['brandSlug' => $topViewedProduct->brand->slug]) }}"
                                                                    class="font-size-12 text-gray-5">{{ $topViewedProduct->brand ? $topViewedProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                            </div>
                                                            <h5 class="mb-4 product-item__title truncate-title">
                                                                <a href="{{ route('client.products.detail', $topViewedProduct->slug) }}"
                                                                    class="text-blue font-weight-bold product-name">
                                                                    {{ Str::limit($topViewedProduct->name, 22) }}
                                                                </a>
                                                            </h5>

                                                            <div class="">
                                                                <div class="prodcut-price mt-3">
                                                                    @if ($topViewedProduct->is_variant && $topViewedProduct->variants->count())
                                                                        @php
                                                                            $prices = $topViewedProduct->variants->pluck(
                                                                                'price',
                                                                            );
                                                                            $salePrices = $topViewedProduct->variants
                                                                                ->pluck('price_sale')
                                                                                ->filter();
                                                                            $minPrice = $salePrices->count()
                                                                                ? $salePrices->min()
                                                                                : $prices->min();
                                                                            $originalMin = $prices->min();
                                                                        @endphp
                                                                        @if ($salePrices->count())
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                        @endif
                                                                    @elseif ($topViewedProduct->price_sale && $topViewedProduct->price_sale > 0)
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($topViewedProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($topViewedProduct->price, 0, ',', '.') }}đ</del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($topViewedProduct->price, 0, ',', '.') }}đ</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- End Featured Products -->
                    </aside>
                    <!-- End Feature Product -->
                    <!-- From the Blog -->
                    <aside class="mb-8">
                        <div class="position-relative">
                            <div class="border-bottom border-color-1 mb-4">
                                <h3 class="section-title mb-0 pb-3 font-size-18">Bài viết mới</h3>
                            </div>
                            <div class="js-slick-carousel u-slick position-static overflow-hidden u-slick-overflow-visble"
                                data-slides-show="1" data-slides-scroll="1"
                                data-arrows-classes="position-absolute top-0 font-size-17 u-slick__arrow-normal top-10"
                                data-arrow-left-classes="fa fa-angle-left right-1"
                                data-arrow-right-classes="fa fa-angle-right right-0">

                                @foreach ($recentPosts as $post)
                                    <div class="js-slide post-group">
                                        <div class="post-item">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-3">
                                                    <a href="{{ route('blog.show', $post->slug) }}"
                                                        class="d-block text-center">
                                                        <img class="img-fluid"
                                                            src="{{ asset('storage/' . $post->image) }}"
                                                            alt="{{ $post->title }}"
                                                            style="width: 270px !important; height: 180px !important; object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div class="mb-1">
                                                    <a href="#" class="font-size-12 text-gray-5">
                                                        {{ $post->category_post->name ?? 'Không có danh mục' }}
                                                    </a>
                                                </div>
                                                <h6 class="mb-2 post-item__title font-size-14">
                                                    <a href="{{ route('blog.show', $post->slug) }}"
                                                        class="font-weight-bold text-dark">{{ $post->title }}</a>
                                                </h6>
                                                <div class="mb-1">
                                                    <a href="{{ route('blog.show', $post->slug) }}"
                                                        class="d-block text-gray-5">
                                                        <i class="ec ec-comment"></i> {{ $post->comments_count ?? 0 }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </aside>

                    <!-- End From the Blog -->
                </div>
            </div>
            <div class="col-xl-9 col-wd-auto max-width-1130">
                <!-- Banner -->
                <div class="row mb-6">
                    <div class="col-md-6 mb-4 mb-xl-0 col-wd-4">
                        <a href="{{ route('client.products.index') }}" class="d-black text-black">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/190x150/img3.jpg"
                                        alt="Laptop Mới">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        ƯU ĐÃI LỚN <strong>LAPTOP MỚI</strong>
                                    </div>
                                    <div class="link text-blackblack font-weight-bold font-size-15">
                                        Mua ngay
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-4 mb-xl-0 col-wd-4">
                        <a href="{{ route('client.products.index') }}" class="d-black text-black">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/246X176/img2.jpg"
                                        alt="Linh Kiện Máy Tính">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        PHỤ KIỆN & <strong>LINH KIỆN MÁY TÍNH</strong>
                                    </div>
                                    <div class="link text-black font-weight-bold font-size-15">
                                        Mua ngay
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-4 mb-xl-0 col-wd-4 d-md-none d-wd-block">
                        <a href="{{ route('client.products.index') }}" class="d-black text-black">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/246X176/img3.jpg" alt="PC Gaming">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        TRẢI NGHIỆM <strong>PC GAMING ĐỈNH CAO</strong>
                                    </div>
                                    <div class="link text-black font-weight-bold font-size-15">
                                        Mua ngay
                                        <span class="link__icon ml-1">
                                            <span class="link__icon-inner"><i
                                                    class="ec ec-arrow-right-categproes"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- End Banner -->

                <!-- Tab Prodcut Section -->
                <div class="mb-6">
                    <!-- Nav Classic -->
                    <div class="position-relative bg-white text-center z-index-2">
                        <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active js-animation-link" id="pills-one-example1-tab"
                                    data-toggle="pill" href="#pills-one-example1" role="tab"
                                    aria-controls="pills-one-example1" aria-selected="true"
                                    data-target="#pills-one-example1" data-link-group="groups"
                                    data-animation-in="slideInUp">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        Sản phẩm nổi bật
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link js-animation-link" id="pills-two-example1-tab" data-toggle="pill"
                                    href="#pills-two-example1" role="tab" aria-controls="pills-two-example1"
                                    aria-selected="false" data-target="#pills-two-example1" data-link-group="groups"
                                    data-animation-in="slideInUp">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        Đang giảm giá
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Classic -->
                    <!-- Tab Content -->
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel"
                            aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                            <div class="mb-8 position-relative home-featured-products-list"
                                style="position: relative;">
                                <div id="homeFeaturedProductListSpinner"
                                    style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <ul class="row list-unstyled products-group no-gutters">
                                    @foreach ($topViewedProducts as $topViewedProduct)
                                        <li class="col-6 col-md-4 col-xl product-item"
                                            data-product-id="{{ $topViewedProduct->id }}">
                                            <div class="product-item__outer h-100">
                                                <div class="product-item__inner px-xl-4 p-3">
                                                    <div class="product-item__body pb-xl-2">
                                                        <div class="mb-2">
                                                            <a href="{{ $topViewedProduct->category?->slug ? route('client.products.category', ['slug' => $topViewedProduct->category->slug]) : '#' }}"
                                                                class="font-size-12 text-gray-5">
                                                                {{ $topViewedProduct->category->name ?? 'Danh mục' }}
                                                            </a>
                                                        </div>
                                                        <h5 class="mb-1 product-item__title truncate-title">
                                                            <a href="{{ route('client.products.detail', $topViewedProduct->slug) }}"
                                                                class="text-blue font-weight-bold">
                                                                {{ Str::limit($topViewedProduct->name, 22) }}
                                                            </a>
                                                        </h5>
                                                        <div class="mb-2">
                                                            <a href="{{ route('client.products.detail', $topViewedProduct->slug) }}"
                                                                class="d-block text-center">
                                                                <img class="img-fluid"
                                                                    style="height: 150px; width: 150px; object-fit: cover;"
                                                                    src="{{ asset('storage/' . $topViewedProduct->thumbnail) }}"
                                                                    alt="{{ $topViewedProduct->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                            style="width: 80px;">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <small
                                                                    class="{{ $i <= $topViewedProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $topViewedProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                            @endfor
                                                        </div>
                                                        <div class="flex-center-between mb-1">
                                                            <div class="prodcut-price mt-3">
                                                                @if ($topViewedProduct->is_variant && $topViewedProduct->variants->count())
                                                                    @php
                                                                        $prices = $topViewedProduct->variants->pluck(
                                                                            'price',
                                                                        );
                                                                        $salePrices = $topViewedProduct->variants
                                                                            ->pluck('price_sale')
                                                                            ->filter();
                                                                        $minPrice = $salePrices->count()
                                                                            ? $salePrices->min()
                                                                            : $prices->min();
                                                                        $originalMin = $prices->min();
                                                                    @endphp
                                                                    @if ($salePrices->count())
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                    @endif
                                                                @elseif ($topViewedProduct->price_sale && $topViewedProduct->price_sale > 0)
                                                                    <div
                                                                        class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                        <ins
                                                                            class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($topViewedProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                        <del
                                                                            class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($topViewedProduct->price, 0, ',', '.') }}đ</del>
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="text-dark fw-bold">{{ number_format($topViewedProduct->price, 0, ',', '.') }}đ</span>
                                                                @endif
                                                            </div>


                                                            <div class="d-none d-xl-block prodcut-add-cart">
                                                                @if ($topViewedProduct->is_variant)
                                                                    <a href="{{ route('client.products.detail', $topViewedProduct->slug) }}"
                                                                        class="btn-add-cart btn-primary transition-3d-hover">
                                                                        <i class="ec ec-add-to-cart"></i>
                                                                    </a>
                                                                @else
                                                                    <form action="{{ route('cart.add') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $topViewedProduct->id }}">
                                                                        <input type="hidden" name="quantity"
                                                                            value="1">
                                                                        <!-- Default to 1 -->
                                                                        <button type="submit"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-item__footer">
                                                        <div class="border-top pt-2 flex-center-between flex-wrap">
                                                            @include('fontend.component.wishlist-button', [
                                                                'product' => $topViewedProduct,
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane fade pt-2" id="pills-two-example1" role="tabpanel"
                            aria-labelledby="pills-two-example1-tab" data-target-group="groups">
                            <div class="mb-8 position-relative home-discounted-products-list"
                                style="position: relative;">
                                <div id="homeDiscountedProductListSpinner"
                                    style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <ul class="row list-unstyled products-group no-gutters">
                                    @foreach ($discountedProducts as $discountedProduct)
                                        <li class="col-6 col-md-4 col-xl product-item"
                                            data-product-id="{{ $discountedProduct->id }}">
                                            <div class="product-item__outer h-100">
                                                <div class="product-item__inner px-xl-4 p-3">
                                                    <div class="product-item__body pb-xl-2">
                                                        <div class="mb-2">
                                                            <a href="{{ $discountedProduct->category?->slug ? route('client.products.category', ['slug' => $discountedProduct->category->slug]) : '#' }}"
                                                                class="font-size-12 text-gray-5">
                                                                {{ $discountedProduct->category->name ?? 'Danh mục' }}
                                                            </a>
                                                        </div>
                                                        <h5 class="mb-1 product-item__title truncate-title">
                                                            <a href="{{ route('client.products.detail', $discountedProduct->slug) }}"
                                                                class="text-blue font-weight-bold">
                                                                {{ Str::limit($discountedProduct->name, 22) }}
                                                            </a>
                                                        </h5>

                                                        <div class="mb-2">
                                                            <a href="{{ route('client.products.detail', $discountedProduct->slug) }}"
                                                                class="d-block text-center">
                                                                <img class="img-fluid w-100"
                                                                    style="height: 150px; object-fit: cover;"
                                                                    src="{{ asset('storage/' . $discountedProduct->thumbnail) }}"
                                                                    alt="{{ $discountedProduct->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                            style="width: 80px;">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <small
                                                                    class="{{ $i <= $discountedProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $discountedProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                            @endfor
                                                        </div>
                                                        <div class="flex-center-between mb-1 product-price">
                                                            <div class="prodcut-price mt-3">
                                                                @if ($discountedProduct->is_variant && $discountedProduct->variants->count())
                                                                    @php
                                                                        $prices = $discountedProduct->variants->pluck(
                                                                            'price',
                                                                        );
                                                                        $salePrices = $discountedProduct->variants
                                                                            ->pluck('price_sale')
                                                                            ->filter();
                                                                        $minPrice = $salePrices->count()
                                                                            ? $salePrices->min()
                                                                            : $prices->min();
                                                                        $originalMin = $prices->min();
                                                                    @endphp
                                                                    @if ($salePrices->count())
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                    @endif
                                                                @elseif ($discountedProduct->price_sale && $discountedProduct->price_sale > 0)
                                                                    <div
                                                                        class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                        <ins
                                                                            class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($discountedProduct->price_sale) }}đ</ins>
                                                                        <del
                                                                            class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($discountedProduct->price, 0, ',', '.') }}đ</del>
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="text-dark fw-bold">{{ number_format($discountedProduct->price, 0, ',', '.') }}đ</span>
                                                                @endif
                                                            </div>


                                                            <div class="d-none d-xl-block prodcut-add-cart">
                                                                @if ($discountedProduct->is_variant)
                                                                    <a href="{{ route('client.products.detail', $discountedProduct->slug) }}"
                                                                        class="btn-add-cart btn-primary transition-3d-hover">
                                                                        <i class="ec ec-add-to-cart"></i>
                                                                    </a>
                                                                @else
                                                                    <form action="{{ route('cart.add') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $discountedProduct->id }}">
                                                                        <input type="hidden" name="quantity"
                                                                            value="1">
                                                                        <!-- Default to 1 -->
                                                                        <button type="submit"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-item__footer">
                                                        <div class="border-top pt-2 flex-center-between flex-wrap">
                                                            <a href="#" class="text-gray-6 font-size-13"><i
                                                                    class="ec ec-favorites mr-1 font-size-15"></i> Yêu
                                                                thích</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Tab Content -->
                </div>
                <!-- End Tab Prodcut Section -->

                <!-- Full banner -->
                <div class="mb-8">
                    <a href="{{ route('client.products.index') }}" class="d-block text-black">
                        <div class="bg-img-hero pt-3"
                            style="background-image: url(fontend/assets/img/1400X206/img1.jpg);">
                            <div class="space-top-2-md p-4 pt-4 pt-md-5 pt-lg-6 pt-xl-5 pb-lg-4 px-xl-8 px-lg-6">
                                <div class="flex-horizontal-center overflow-auto overflow-md-visible">
                                    <h1
                                        class="text-lh-38 font-size-24 font-weight-light mb-0 flex-shrink-0 flex-md-shrink-1">
                                        MUA SẮM & <strong>TIẾT KIỆM LỚN</strong> VỚI LAPTOP HOT NHẤT
                                    </h1>
                                    <div class="flex-content-center ml-4 flex-shrink-0">
                                        <div class="bg-primary rounded-lg px-6 py-2">
                                            <em class="font-size-14 font-weight-light text-white">GIÁ CHỈ TỪ</em>
                                            <div class="font-size-30 font-weight-bold text-lh-1">
                                                <sup class=""></sup>10.500K<sup class="">Đ</sup>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                </div>
                <!-- End Full banner -->
                <!-- Prodcut-cards-carousel -->
                <div class="mb-8">
                    <dv
                        class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Bán chạy nhất</h3>
                    </dv>
                    <div class="mb-8 position-relative home-top-selling-products-list" style="position: relative;">
                        <div id="homeTopSellingProductListSpinner"
                            style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="js-slick-carousel u-slick u-slick--gutters-1 overflow-hidden u-slick-overflow-visble pt-3 pb-6"
                            data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4">
                            <div class="js-slide">
                                <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                    @foreach ($topSellingProducts as $key => $topSellingProduct)
                                        @if ($key < 3)
                                            <li class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0"
                                                data-product-id="{{ $topSellingProduct->id }}">
                                                <div class="product-item__outer h-100">
                                                    <div class="product-item__inner p-md-3 row no-gutters">
                                                        <div
                                                            class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                            <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                class="max-width-150 d-block"><img class="img-fluid"
                                                                    src="{{ asset('storage/' . $topSellingProduct->thumbnail) }}"
                                                                    alt="Image Description"
                                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                                        </div>
                                                        <div
                                                            class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                            <div>

                                                                <div class="mb-2"><a
                                                                        href="{{ route('client.products.brand', ['brandSlug' => $topSellingProduct->brand->slug]) }}"
                                                                        class="font-size-12 text-gray-5">{{ $topSellingProduct->brand ? $topSellingProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                                </div>
                                                                <h5 class="product-item__title truncate-title">
                                                                    <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                        class="text-blue font-weight-bold">{{ Str::limit($topSellingProduct->name, 22) }}</a>
                                                                </h5>

                                                            </div>
                                                            <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                                style="width: 80px;">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <small
                                                                        class="{{ $i <= $topSellingProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $topSellingProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                                @endfor
                                                            </div>
                                                            <div class="flex-center-between mb-3 product-price">
                                                                <div class="prodcut-price mt-3">
                                                                    @if ($topSellingProduct->is_variant && $topSellingProduct->variants->count())
                                                                        @php
                                                                            $prices = $topSellingProduct->variants->pluck(
                                                                                'price',
                                                                            );
                                                                            $salePrices = $topSellingProduct->variants
                                                                                ->pluck('price_sale')
                                                                                ->filter();
                                                                            $minPrice = $salePrices->count()
                                                                                ? $salePrices->min()
                                                                                : $prices->min();
                                                                            $originalMin = $prices->min();
                                                                        @endphp
                                                                        @if ($salePrices->count())
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                    {{ number_format($minPrice, 0, ',', '.') }}
                                                                                    đ
                                                                                </ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                    {{ number_format($originalMin, 0, ',', '.') }}
                                                                                    đ
                                                                                </del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}
                                                                                đ</span>
                                                                        @endif
                                                                    @elseif ($topSellingProduct->price_sale && $topSellingProduct->price_sale > 0)
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                {{ number_format($topSellingProduct->price_sale, 0, ',', '.') }}
                                                                                đ
                                                                            </ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                {{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                                đ
                                                                            </del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                            đ</span>
                                                                    @endif
                                                                </div>
                                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                                    @if ($topSellingProduct->is_variant)
                                                                        <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </a>
                                                                    @else
                                                                        <form action="{{ route('cart.add') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_id"
                                                                                value="{{ $topSellingProduct->id }}">
                                                                            <input type="hidden" name="quantity"
                                                                                value="1">
                                                                            <button type="submit"
                                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                                <i class="ec ec-add-to-cart"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product-item__footer">
                                                                <div
                                                                    class="border-top pt-2 flex-center-between flex-wrap">
                                                                    @include(
                                                                        'fontend.component.wishlist-button',
                                                                        ['product' => $topSellingProduct]
                                                                    )
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="js-slide">
                                <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                    @foreach ($topSellingProducts as $key => $topSellingProduct)
                                        @if ($key >= 3 && $key < 6)
                                            <li class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0"
                                                data-product-id="{{ $topSellingProduct->id }}">
                                                <div class="product-item__outer h-100">
                                                    <div class="product-item__inner p-md-3 row no-gutters">
                                                        <div
                                                            class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                            <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                class="max-width-150 d-block"><img class="img-fluid"
                                                                    src="{{ asset('storage/' . $topSellingProduct->thumbnail) }}"
                                                                    alt="Image Description"
                                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                                        </div>
                                                        <div
                                                            class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                            <div>
                                                                <div class="mb-2"><a
                                                                        href="{{ route('client.products.brand', ['brandSlug' => $topSellingProduct->brand->slug]) }}"
                                                                        class="font-size-12 text-gray-5">{{ $topSellingProduct->brand ? $topSellingProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                                </div>
                                                                <h5 class="product-item__title truncate-title">
                                                                    <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                        class="text-blue font-weight-bold">{{ Str::limit($topSellingProduct->name, 22) }}</a>
                                                                </h5>
                                                            </div>
                                                            <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                                style="width: 80px;">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <small
                                                                        class="{{ $i <= $topSellingProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $topSellingProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                                @endfor
                                                            </div>
                                                            <div class="flex-center-between mb-3 product-price">
                                                                <div class="prodcut-price mt-3">
                                                                    @if ($topSellingProduct->is_variant && $topSellingProduct->variants->count())
                                                                        @php
                                                                            $prices = $topSellingProduct->variants->pluck(
                                                                                'price',
                                                                            );
                                                                            $salePrices = $topSellingProduct->variants
                                                                                ->pluck('price_sale')
                                                                                ->filter();
                                                                            $minPrice = $salePrices->count()
                                                                                ? $salePrices->min()
                                                                                : $prices->min();
                                                                            $originalMin = $prices->min();
                                                                        @endphp
                                                                        @if ($salePrices->count())
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                    {{ number_format($minPrice, 0, ',', '.') }}
                                                                                    đ
                                                                                </ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                    {{ number_format($originalMin, 0, ',', '.') }}
                                                                                    đ
                                                                                </del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}
                                                                                đ</span>
                                                                        @endif
                                                                    @elseif ($topSellingProduct->price_sale && $topSellingProduct->price_sale > 0)
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                {{ number_format($topSellingProduct->price_sale, 0, ',', '.') }}
                                                                                đ
                                                                            </ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                {{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                                đ
                                                                            </del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                            đ</span>
                                                                    @endif
                                                                </div>
                                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                                    @if ($topSellingProduct->is_variant)
                                                                        <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </a>
                                                                    @else
                                                                        <form action="{{ route('cart.add') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_id"
                                                                                value="{{ $topSellingProduct->id }}">
                                                                            <input type="hidden" name="quantity"
                                                                                value="1">
                                                                            <button type="submit"
                                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                                <i class="ec ec-add-to-cart"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- <div class="product-item__footer">
                                                                <div
                                                                    class="border-top pt-2 flex-center-between flex-wrap">
                                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                                    class="text-gray-6 font-size-13"><i
                                                                        class="ec ec-compare mr-1 font-size-15"></i>
                                                                    Compare</a>
                                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                                    class="text-gray-6 font-size-13"><i
                                                                        class="ec ec-favorites mr-1 font-size-15"></i>
                                                                    Wishlist</a>
                                                            </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="js-slide">
                                <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                    @foreach ($topSellingProducts as $key => $topSellingProduct)
                                        @if ($key >= 6 && $key < 9)
                                            <li class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0"
                                                data-product-id="{{ $topSellingProduct->id }}">
                                                <div class="product-item__outer h-100">
                                                    <div class="product-item__inner p-md-3 row no-gutters">
                                                        <div
                                                            class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                            <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                class="max-width-150 d-block"><img class="img-fluid"
                                                                    src="{{ asset('storage/' . $topSellingProduct->thumbnail) }}"
                                                                    alt="Image Description"
                                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                                        </div>
                                                        <div
                                                            class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                            <div>
                                                                <div class="mb-2"><a
                                                                        href="{{ route('client.products.brand', ['brandSlug' => $topSellingProduct->brand->slug]) }}"
                                                                        class="font-size-12 text-gray-5">{{ $topSellingProduct->brand ? $topSellingProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                                </div>
                                                                <h5 class="product-item__title truncate-title">
                                                                    <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                        class="text-blue font-weight-bold">{{ Str::limit($topSellingProduct->name, 22) }}</a>
                                                                </h5>
                                                            </div>
                                                            <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                                style="width: 80px;">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <small
                                                                        class="{{ $i <= $topSellingProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $topSellingProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                                @endfor
                                                            </div>
                                                            <div class="flex-center-between mb-3 product-price">
                                                                <div class="prodcut-price mt-3">
                                                                    @if ($topSellingProduct->is_variant && $topSellingProduct->variants->count())
                                                                        @php
                                                                            $prices = $topSellingProduct->variants->pluck(
                                                                                'price',
                                                                            );
                                                                            $salePrices = $topSellingProduct->variants
                                                                                ->pluck('price_sale')
                                                                                ->filter();
                                                                            $minPrice = $salePrices->count()
                                                                                ? $salePrices->min()
                                                                                : $prices->min();
                                                                            $originalMin = $prices->min();
                                                                        @endphp
                                                                        @if ($salePrices->count())
                                                                            <div
                                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                                <ins
                                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                    {{ number_format($minPrice, 0, ',', '.') }}
                                                                                    đ
                                                                                </ins>
                                                                                <del
                                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                    {{ number_format($originalMin, 0, ',', '.') }}
                                                                                    đ
                                                                                </del>
                                                                            </div>
                                                                        @else
                                                                            <span
                                                                                class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}
                                                                                đ</span>
                                                                        @endif
                                                                    @elseif ($topSellingProduct->price_sale && $topSellingProduct->price_sale > 0)
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">
                                                                                {{ number_format($topSellingProduct->price_sale, 0, ',', '.') }}
                                                                                đ
                                                                            </ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">
                                                                                {{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                                đ
                                                                            </del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($topSellingProduct->price, 0, ',', '.') }}
                                                                            đ</span>
                                                                    @endif
                                                                </div>
                                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                                    @if ($topSellingProduct->is_variant)
                                                                        <a href="{{ route('client.products.detail', $topSellingProduct->slug) }}"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </a>
                                                                    @else
                                                                        <form action="{{ route('cart.add') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_id"
                                                                                value="{{ $topSellingProduct->id }}">
                                                                            <input type="hidden" name="quantity"
                                                                                value="1">
                                                                            <button type="submit"
                                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                                <i class="ec ec-add-to-cart"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- <div class="product-item__footer">
                                                                <div
                                                                    class="border-top pt-2 flex-center-between flex-wrap">
                                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                                    class="text-gray-6 font-size-13"><i
                                                                        class="ec ec-compare mr-1 font-size-15"></i>
                                                                    Compare</a>
                                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                                    class="text-gray-6 font-size-13"><i
                                                                        class="ec ec-favorites mr-1 font-size-15"></i>
                                                                    Wishlist</a>
                                                            </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Prodcut-cards-carousel -->
                <!-- Product-with-banner -->
                <div class="mb-8">
                    <dv
                        class=" d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Laptop & PC</h3>
                    </dv>
                    <div class="mb-8 position-relative home-category-products-list" style="position: relative;">
                        <div id="homeCategoryProductListSpinner"
                            style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <a href="{{ route('client.products.index') }}" class="d-block">
                                    <img class="img-fluid" src="fontend/assets/img/212X305/img2.jpg"
                                        alt="Image Description">
                                </a>
                            </div>
                            <div class="col">
                                <ul class="row list-unstyled products-group no-gutters">
                                    @foreach ($products as $product)
                                        <li class="col-6 col-md-4 col-wd-3 product-item"
                                            data-product-id="{{ $product->id }}">
                                            <div class="product-item__outer h-100">
                                                <div class="product-item__inner px-xl-4 p-3">
                                                    <div class="product-item__body pb-xl-2">
                                                        <div class="mb-2">
                                                            <a href="{{ $product->category?->slug ? route('client.products.category', ['slug' => $product->category->slug]) : '#' }}"
                                                                class="font-size-12 text-gray-5">
                                                                {{ $product->category->name ?? 'Danh mục' }}
                                                            </a>
                                                        </div>
                                                        <h5 class="mb-1 product-item__title truncate-title">
                                                            <a href="{{ route('client.products.detail', $product->slug) }}"
                                                                class="text-blue font-weight-bold product-name">
                                                                {{ Str::limit($product->name, 22) }}
                                                            </a>
                                                        </h5>
                                                        <div class="mb-2">
                                                            <a href="{{ route('client.products.detail', $product->slug) }}"
                                                                class="d-block text-center">
                                                                <img class="img-fluid w-100"
                                                                    style="height: 150px; object-fit: cover;"
                                                                    src="{{ asset('storage/' . $product->thumbnail) }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                            style="width: 80px;">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <small
                                                                    class="{{ $i <= $product->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $product->average_rating ? 'text-muted' : '' }}"></small>
                                                            @endfor
                                                        </div>
                                                        <div class="flex-center-between mb-1">
                                                            <div class="prodcut-price mt-3">
                                                                @if ($product->is_variant && $product->variants->count())
                                                                    @php
                                                                        $prices = $product->variants->pluck('price');
                                                                        $salePrices = $product->variants
                                                                            ->pluck('price_sale')
                                                                            ->filter();
                                                                        $minPrice = $salePrices->count()
                                                                            ? $salePrices->min()
                                                                            : $prices->min();
                                                                        $originalMin = $prices->min();
                                                                    @endphp
                                                                    @if ($salePrices->count())
                                                                        <div
                                                                            class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                            <ins
                                                                                class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                            <del
                                                                                class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                        </div>
                                                                    @else
                                                                        <span
                                                                            class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                                    @endif
                                                                @elseif ($product->price_sale && $product->price_sale > 0)
                                                                    <div
                                                                        class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                        <ins
                                                                            class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($product->price_sale, 0, ',', '.') }}đ</ins>
                                                                        <del
                                                                            class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="text-dark fw-bold">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                                @endif
                                                            </div>


                                                            <div class="d-none d-xl-block prodcut-add-cart">
                                                                @if ($product->is_variant)
                                                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                                                        class="btn-add-cart btn-primary transition-3d-hover">
                                                                        <i class="ec ec-add-to-cart"></i>
                                                                    </a>
                                                                @else
                                                                    <form action="{{ route('cart.add') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $product->id }}">
                                                                        <input type="hidden" name="quantity"
                                                                            value="1">
                                                                        <button type="submit"
                                                                            class="btn-add-cart btn-primary transition-3d-hover">
                                                                            <i class="ec ec-add-to-cart"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-item__footer">
                                                        <div class="border-top pt-2 flex-center-between flex-wrap">
                                                            @include('fontend.component.wishlist-button', [
                                                                'product' => $product,
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Product-with-banner -->
                <!-- Banner 2 columns -->
                <div class="mb-8">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('client.products.index') }}">
                                <img class="img-fluid" src="fontend/assets/img/536X150/img1.jpg"
                                    alt="Image Description">
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('client.products.index') }}">
                                <img class="img-fluid" src="fontend/assets/img/536X150/img2.jpg"
                                    alt="Image Description">
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Banner 2 columns -->
                <!-- Laptops & Computers -->
                <div class="position-relative">
                    <div
                        class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Chuột & Bàn Phím</h3>
                    </div>
                    <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble pt-3 pb-6 px-1"
                        data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                        data-slides-show="5" data-slides-scroll="1"
                        data-responsive='[{
                          "breakpoint": 1400,
                          "settings": {
                            "slidesToShow": 4
                          }
                        }, {
                            "breakpoint": 1200,
                            "settings": {
                              "slidesToShow": 3
                            }
                        }, {
                          "breakpoint": 992,
                          "settings": {
                            "slidesToShow": 3
                          }
                        }, {
                          "breakpoint": 768,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }, {
                          "breakpoint": 554,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }]'>
                        @foreach ($keyboardMouseProducts as $keyboardMouseProduct)
                            <div class="js-slide products-group">
                                <div class="product-item">
                                    <div class="product-item__outer h-100"
                                        data-product-id="{{ $keyboardMouseProduct->id }}">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2">
                                                    <a href="{{ $keyboardMouseProduct->category?->slug ? route('client.products.category', ['slug' => $keyboardMouseProduct->category->slug]) : '#' }}"
                                                        class="font-size-12 text-gray-5">
                                                        {{ $keyboardMouseProduct->category->name ?? 'Danh mục' }}
                                                    </a>
                                                </div>
                                                <h5 class="mb-1 product-item__title truncate-title">
                                                    <a href="{{ route('client.products.detail', $keyboardMouseProduct->slug) }}"
                                                        class="text-blue font-weight-bold">
                                                        {{ Str::limit($keyboardMouseProduct->name, 22) }}
                                                    </a>
                                                </h5>
                                                <div class="mb-2">
                                                    <a href="{{ route('client.products.detail', $keyboardMouseProduct->slug) }}"
                                                        class="d-block text-center">
                                                        <img class="img-fluid w-100"
                                                            style="height: 150px; object-fit: cover;"
                                                            src="{{ asset('storage/' . $keyboardMouseProduct->thumbnail) }}"
                                                            alt="{{ $keyboardMouseProduct->name }}">
                                                    </a>
                                                </div>
                                                <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                    style="width: 80px;">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <small
                                                            class="{{ $i <= $keyboardMouseProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $keyboardMouseProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                    @endfor
                                                </div>
                                                <div class="flex-center-between mb-1 product-price">
                                                    <div class="prodcut-price mt-3">
                                                        @if ($keyboardMouseProduct->is_variant && $keyboardMouseProduct->variants->count())
                                                            @php
                                                                $prices = $keyboardMouseProduct->variants->pluck(
                                                                    'price',
                                                                );
                                                                $salePrices = $keyboardMouseProduct->variants
                                                                    ->pluck('price_sale')
                                                                    ->filter();
                                                                $minPrice = $salePrices->count()
                                                                    ? $salePrices->min()
                                                                    : $prices->min();
                                                                $originalMin = $prices->min();
                                                            @endphp
                                                            @if ($salePrices->count())
                                                                <div
                                                                    class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                    <ins
                                                                        class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                    <del
                                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                </div>
                                                            @else
                                                                <span
                                                                    class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                            @endif
                                                        @elseif ($keyboardMouseProduct->price_sale && $keyboardMouseProduct->price_sale > 0)
                                                            <div
                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                <ins
                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($keyboardMouseProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                <del
                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($keyboardMouseProduct->price, 0, ',', '.') }}đ</del>
                                                            </div>
                                                        @else
                                                            <span
                                                                class="text-dark fw-bold">{{ number_format($keyboardMouseProduct->price, 0, ',', '.') }}đ</span>
                                                        @endif
                                                    </div>


                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        @if ($keyboardMouseProduct->is_variant)
                                                            <a href="{{ route('client.products.detail', $keyboardMouseProduct->slug) }}"
                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                <i class="ec ec-add-to-cart"></i>
                                                            </a>
                                                        @else
                                                            <form action="{{ route('cart.add') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $keyboardMouseProduct->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit"
                                                                    class="btn-add-cart btn-primary transition-3d-hover">
                                                                    <i class="ec ec-add-to-cart"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    @include('fontend.component.wishlist-button', [
                                                        'product' => $keyboardMouseProduct,
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- End Laptops & Computers -->

                <div class="position-relative">
                    <div
                        class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Màn hình</h3>
                    </div>
                    <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble pt-3 pb-6 px-1"
                        data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                        data-slides-show="5" data-slides-scroll="1"
                        data-responsive='[{
                          "breakpoint": 1400,
                          "settings": {
                            "slidesToShow": 4
                          }
                        }, {
                            "breakpoint": 1200,
                            "settings": {
                              "slidesToShow": 3
                            }
                        }, {
                          "breakpoint": 992,
                          "settings": {
                            "slidesToShow": 3
                          }
                        }, {
                          "breakpoint": 768,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }, {
                          "breakpoint": 554,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }]'>
                        @foreach ($screenProducts as $screenProduct)
                            <div class="js-slide products-group">
                                <div class="product-item">
                                    <div class="product-item__outer h-100"
                                        data-product-id="{{ $screenProduct->id }}">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2">
                                                    <a href="{{ $screenProduct->category?->slug ? route('client.products.category', ['slug' => $keyboardMouseProduct->category->slug]) : '#' }}"
                                                        class="font-size-12 text-gray-5">
                                                        {{ $screenProduct->category->name ?? 'Danh mục' }}
                                                    </a>
                                                </div>
                                                <h5 class="mb-1 product-item__title truncate-title">
                                                    <a href="{{ route('client.products.detail', $screenProduct->slug) }}"
                                                        class="text-blue font-weight-bold">
                                                        {{ Str::limit($screenProduct->name, 22) }}
                                                    </a>
                                                </h5>
                                                <div class="mb-2">
                                                    <a href="{{ route('client.products.detail', $screenProduct->slug) }}"
                                                        class="d-block text-center">
                                                        <img class="img-fluid w-100"
                                                            style="height: 150px; object-fit: cover;"
                                                            src="{{ asset('storage/' . $screenProduct->thumbnail) }}"
                                                            alt="{{ $screenProduct->name }}">
                                                    </a>
                                                </div>
                                                <div class="text-warning text-ls-n2 font-size-16 mb-1"
                                                    style="width: 80px;">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <small
                                                            class="{{ $i <= $screenProduct->average_rating ? 'fas' : 'far' }} fa-star {{ $i > $screenProduct->average_rating ? 'text-muted' : '' }}"></small>
                                                    @endfor
                                                </div>
                                                <div class="flex-center-between mb-1 product-price">
                                                    <div class="prodcut-price mt-3">
                                                        @if ($screenProduct->is_variant && $screenProduct->variants->count())
                                                            @php
                                                                $prices = $screenProduct->variants->pluck('price');
                                                                $salePrices = $screenProduct->variants
                                                                    ->pluck('price_sale')
                                                                    ->filter();
                                                                $minPrice = $salePrices->count()
                                                                    ? $salePrices->min()
                                                                    : $prices->min();
                                                                $originalMin = $prices->min();
                                                            @endphp
                                                            @if ($salePrices->count())
                                                                <div
                                                                    class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                    <ins
                                                                        class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($minPrice, 0, ',', '.') }}đ</ins>
                                                                    <del
                                                                        class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($originalMin, 0, ',', '.') }}đ</del>
                                                                </div>
                                                            @else
                                                                <span
                                                                    class="text-dark fw-bold">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                                            @endif
                                                        @elseif ($screenProduct->price_sale && $screenProduct->price_sale > 0)
                                                            <div
                                                                class="prodcut-price mt-3 d-flex align-items-center position-relative">
                                                                <ins
                                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($screenProduct->price_sale, 0, ',', '.') }}đ</ins>
                                                                <del
                                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100">{{ number_format($screenProduct->price, 0, ',', '.') }}đ</del>
                                                            </div>
                                                        @else
                                                            <span
                                                                class="text-dark fw-bold">{{ number_format($screenProduct->price, 0, ',', '.') }}đ</span>
                                                        @endif
                                                    </div>


                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        @if ($screenProduct->is_variant)
                                                            <a href="{{ route('client.products.detail', $screenProduct->slug) }}"
                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                <i class="ec ec-add-to-cart"></i>
                                                            </a>
                                                        @else
                                                            <form action="{{ route('cart.add') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $screenProduct->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit"
                                                                    class="btn-add-cart btn-primary transition-3d-hover">
                                                                    <i class="ec ec-add-to-cart"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    @include('fontend.component.wishlist-button', [
                                                        'product' => $keyboardMouseProduct,
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Brand Carousel -->
    <div class="container mb-8">
        <div class="py-2 border-top border-bottom">
            <div class="js-slick-carousel u-slick my-1" data-slides-show="5" data-slides-scroll="1"
                data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-normal u-slick__arrow-centered--y"
                data-arrow-left-classes="fa fa-angle-left u-slick__arrow-classic-inner--left z-index-9"
                data-arrow-right-classes="fa fa-angle-right u-slick__arrow-classic-inner--right"
                data-responsive='[{
                    "breakpoint": 992,
                    "settings": {
                        "slidesToShow": 2
                    }
                }, {
                    "breakpoint": 768,
                    "settings": {
                        "slidesToShow": 1
                    }
                }, {
                    "breakpoint": 554,
                    "settings": {
                        "slidesToShow": 1
                    }
                }]'>
                @foreach ($brands as $brand)
                    <div class="js-slide">
                        <a href="#" class="link-hover__brand">
                            <img class="img-fluid m-auto max-height-50" src="{{ asset('storage/' . $brand->logo) }}"
                                alt="Image Description">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- End Brand Carousel -->
</main>
