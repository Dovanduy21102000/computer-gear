<main id="content" role="main">
    <!-- Slider Section -->
    <div class="mb-4">
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
    </style>
    <!-- End Slider Section -->

    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-wd-auto d-none d-xl-block">
                <div class="max-width-270 min-width-270 pt-xl-13 mt-xl-13">

                    <!-- Latest Products -->
                    <aside class="mb-4">
                        <!-- Wrapper Latest Products -->
                        <div class="mb-2 position-relative">
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
                                        @foreach ($products as $product)
                                            <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                                <div class="product-item__outer h-100">
                                                    <div
                                                        class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                        <div class="col-auto product-media-left">
                                                            <a href="#" class="max-width-70 d-block"><img
                                                                    class="img-fluid"
                                                                    src="{{ asset('storage/' . $product->thumbnail) }}"
                                                                    alt="Image Description"></a>
                                                        </div>
                                                        <div class="col product-item__body pl-2 pl-lg-3">
                                                            <div class="mb-4">
                                                                <h5 class="product-item__title"><a
                                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                        class="text-gray-90">{{ $product->name }}</a>
                                                                </h5>
                                                            </div>
                                                            <div class="flex-center-between">
                                                                <div class="prodcut-price">
                                                                    <div
                                                                        class="text-gray-100 font-size-15 font-weight-bold">
                                                                        @if ($product->price_sale && $product->price_sale > 0)
                                                                            {{ number_format($product->price_sale, 0, ',', '.') }}
                                                                            đ
                                                                        @else
                                                                            {{ number_format($product->price, 0, ',', '.') }}
                                                                            đ
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                {{-- <div class="js-slide">
                                    <ul class="list-unstyled products-group mb-0 overflow-visible">
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img1.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Tablet Air 3 WiFi 64GB
                                                                    Gold</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $629,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img2.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Tablet White EliteBook Revolve
                                                                    810 G2</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $1 299,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img3.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Pendrive USB 3.0 Flash 64
                                                                    GB</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img7.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">White Solo 2 Wireless</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img4.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Smartwatch 2.0 LTE Wifi</a>
                                                            </h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img5.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Gear Virtual Reality</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $799,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img6.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">External SSD USB 3.1 750
                                                                    GB</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $799,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div class="product-item__inner py-md-3 mx-3 row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img8.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Purple NX Mini F1 aparat SMART
                                                                    NX</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $559.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="js-slide">
                                    <ul class="list-unstyled products-group mb-0 overflow-visible">
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img1.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Tablet Air 3 WiFi 64GB
                                                                    Gold</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $629,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img2.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Tablet White EliteBook Revolve
                                                                    810 G2</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $1 299,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img3.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Pendrive USB 3.0 Flash 64
                                                                    GB</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img7.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">White Solo 2 Wireless</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img4.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Smartwatch 2.0 LTE Wifi</a>
                                                            </h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $110,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img5.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Gear Virtual Reality</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $799,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div
                                                    class="product-item__inner py-md-3 mx-3 border-bottom row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img6.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">External SSD USB 3.1 750
                                                                    GB</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $799,00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="product-item__list pb-2 mb-2 pb-md-0 mb-md-0">
                                            <div class="product-item__outer h-100">
                                                <div class="product-item__inner py-md-3 mx-3 row no-gutters">
                                                    <div class="col-auto product-media-left">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="max-width-70 d-block"><img class="img-fluid"
                                                                src="fontend/assets/img/150X140/img8.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="col product-item__body pl-2 pl-lg-3">
                                                        <div class="mb-4">
                                                            <h5 class="product-item__title"><a
                                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                    class="text-gray-90">Purple NX Mini F1 aparat SMART
                                                                    NX</a></h5>
                                                        </div>
                                                        <div class="flex-center-between">
                                                            <div class="prodcut-price">
                                                                <div
                                                                    class="text-gray-100 font-size-15 font-weight-bold">
                                                                    $559.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                        <!-- End Wrapper Latest Products -->
                    </aside>
                    <!-- End Latest Products -->
                    <!-- Feature List -->
                    <aside class="mb-8">
                        <div class="d-flex justify-content-center rounded border mb-4">
                            <div class="px-4 py-6 w-100">
                                <!-- Feature List -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-transport font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Free Delivery</span>
                                        <div class=" text-secondary">from $50</div>
                                    </div>
                                </div>
                                <!-- End Feature List -->

                                <!-- Feature List -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-customers font-size-56"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">99 % Customer</span>
                                        <div class=" text-secondary">Feedbacks</div>
                                    </div>
                                </div>
                                <!-- End Feature List -->

                                <!-- Feature List -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-returning font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">365 Days</span>
                                        <div class=" text-secondary">for free return</div>
                                    </div>
                                </div>
                                <!-- End Feature List -->

                                <!-- Feature List -->
                                <div class="media px-3 mb-4 pb-4 border-bottom" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-payment font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Payment</span>
                                        <div class=" text-secondary">Secure System</div>
                                    </div>
                                </div>
                                <!-- End Feature List -->

                                <!-- Feature List -->
                                <div class="media px-3" href="#">
                                    <div class="u-avatar mr-2">
                                        <i class="text-primary ec ec-tag font-size-46"></i>
                                    </div>
                                    <div class="media-body text-center">
                                        <span class="d-block font-weight-bold text-dark">Only Best</span>
                                        <div class=" text-secondary">Brands</div>
                                    </div>
                                </div>
                                <!-- End Feature List -->
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
                            <div class="js-slick-carousel u-slick position-static overflow-hidden u-slick-overflow-visble"
                                data-slides-show="1" data-slides-scroll="1"
                                data-arrows-classes="position-absolute top-0 font-size-17 u-slick__arrow-normal top-10"
                                data-arrow-left-classes="fa fa-angle-left right-1"
                                data-arrow-right-classes="fa fa-angle-right right-0">

                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img1.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img2.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img3.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img4.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img5.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img6.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img7.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img1.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide products-group">
                                    <div class="product-item remove-divider text-center">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner remove-prodcut-hover px-wd-4 p-2 p-md-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="fontend/assets/img/212X200/img1.jpg"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Speakers</a></div>
                                                    <h5 class="mb-4 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Wireless Audio System
                                                            Multiroom 360 degree Full base audio</a></h5>
                                                    <div class="mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">$685,00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                <h3 class="section-title mb-0 pb-3 font-size-18">Bài viết</h3>
                            </div>
                            <div class="js-slick-carousel u-slick position-static overflow-hidden u-slick-overflow-visble"
                                data-slides-show="1" data-slides-scroll="1"
                                data-arrows-classes="position-absolute top-0 font-size-17 u-slick__arrow-normal top-10"
                                data-arrow-left-classes="fa fa-angle-left right-1"
                                data-arrow-right-classes="fa fa-angle-right right-0">
                                <div class="js-slide post-group">
                                    <div class="post-item">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-3">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/270X180/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="mb-1"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h6 class="mb-2 post-item__title font-size-14"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="font-weight-bold text-dark">Robot Wars – Post with
                                                    Gallery</a></h6>
                                            <div class="mb-1">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="d-block text-gray-5"><i class="ec ec-comment"></i> 3</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-slide post-group">
                                    <div class="post-item">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-3">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/270X180/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="mb-1"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h6 class="mb-2 post-item__title font-size-14"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="font-weight-bold text-dark">Robot Wars – Post with
                                                    Gallery</a></h6>
                                            <div class="mb-1">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/blog/single-blog-post.html"
                                                    class="d-block text-gray-5"><i class="ec ec-comment"></i> 3</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        <a href="/shop" class="d-black text-gray-90">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/190x150/img3.jpg"
                                        alt="Laptop Mới">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        ƯU ĐÃI LỚN <strong>LAPTOP MỚI</strong>
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15">
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
                        <a href="/shop" class="d-black text-gray-90">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/246X176/img2.jpg"
                                        alt="Linh Kiện Máy Tính">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        PHỤ KIỆN & <strong>LINH KIỆN MÁY TÍNH</strong>
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15">
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
                        <a href="/shop" class="d-black text-gray-90">
                            <div class="min-height-166 py-1 py-xl-2 py-wd-4 d-flex bg-gray-1 align-items-center">
                                <div class="col-6 col-xl-7 col-wd-6 pr-0">
                                    <img class="img-fluid" src="fontend/assets/img/246X176/img3.jpg" alt="PC Gaming">
                                </div>
                                <div class="col-6 col-xl-5 col-wd-6 pr-xl-4 pr-wd-3">
                                    <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                        TRẢI NGHIỆM <strong>PC GAMING ĐỈNH CAO</strong>
                                    </div>
                                    <div class="link text-gray-90 font-weight-bold font-size-15">
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
                            <ul class="row list-unstyled products-group no-gutters">
                                @foreach ($topViewedProducts as $topViewedProduct)
                                    <li class="col-6 col-md-4 col-xl product-item">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">{{ $topViewedProduct->brand ? $topViewedProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                    </div>
                                                    <h5 class="mb-1 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">{{ $topViewedProduct->name }}</a>
                                                    </h5>
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="{{ asset('storage/' . $topViewedProduct->thumbnail) }}"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="flex-center-between mb-1">
                                                        <div
                                                            class="prodcut-price d-flex align-items-center position-relative">
                                                            @if ($topViewedProduct->price_sale && $topViewedProduct->price_sale > 0)
                                                                <!-- Hiển thị giá giảm -->
                                                                <ins
                                                                    class="font-size-20 text-red text-decoration-none">
                                                                    {{ number_format($topViewedProduct->price_sale, 0, ',', '.') }}
                                                                    đ
                                                                </ins>
                                                                <!-- Hiển thị giá gốc (gạch ngang) -->
                                                                <del
                                                                    class="font-size-12 text-gray-6 position-absolute bottom-100">
                                                                    {{ number_format($topViewedProduct->price, 0, ',', '.') }}
                                                                    đ
                                                                </del>
                                                            @else
                                                                <!-- Nếu không có giảm giá, chỉ hiển thị giá gốc -->
                                                                <ins
                                                                    class="font-size-20 text-gray-90 text-decoration-none">
                                                                    {{ number_format($topViewedProduct->price, 0, ',', '.') }}
                                                                    đ
                                                                </ins>
                                                            @endif
                                                        </div>
                                                        <div class="d-none d-xl-block prodcut-add-cart">
                                                            <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                    class="ec ec-add-to-cart"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade pt-2" id="pills-two-example1" role="tabpanel"
                            aria-labelledby="pills-two-example1-tab" data-target-group="groups">
                            <ul class="row list-unstyled products-group no-gutters">
                                @foreach ($discountedProducts as $discountedProduct)
                                    <li class="col-6 col-md-4 col-xl product-item">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">{{ $discountedProduct->brand ? $discountedProduct->brand->name : 'Không có thương hiệu' }}</a>
                                                    </div>
                                                    <h5 class="mb-1 product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">{{ $discountedProduct->name }}</a>
                                                    </h5>
                                                    <div class="mb-2">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="d-block text-center"><img class="img-fluid"
                                                                src="{{ asset('storage/' . $discountedProduct->thumbnail) }}"
                                                                alt="Image Description"></a>
                                                    </div>
                                                    <div class="flex-center-between mb-1">
                                                        <div
                                                            class="prodcut-price d-flex align-items-center position-relative">
                                                            @if ($discountedProduct->price_sale && $discountedProduct->price_sale > 0)
                                                                <!-- Hiển thị giá giảm -->
                                                                <ins
                                                                    class="font-size-20 text-red text-decoration-none">
                                                                    {{ number_format($discountedProduct->price_sale, 0, ',', '.') }}
                                                                    đ
                                                                </ins>
                                                                <!-- Hiển thị giá gốc (gạch ngang) -->
                                                                <del
                                                                    class="font-size-12 text-gray-6 position-absolute bottom-100">
                                                                    {{ number_format($discountedProduct->price, 0, ',', '.') }}
                                                                    đ
                                                                </del>
                                                            @else
                                                                <!-- Nếu không có giảm giá, chỉ hiển thị giá gốc -->
                                                                <ins
                                                                    class="font-size-20 text-gray-90 text-decoration-none">
                                                                    {{ number_format($discountedProduct->price, 0, ',', '.') }}
                                                                    đ
                                                                </ins>
                                                            @endif
                                                        </div>
                                                        <div class="d-none d-xl-block prodcut-add-cart">
                                                            <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                                class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                    class="ec ec-add-to-cart"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End Tab Content -->
                </div>
                <!-- End Tab Prodcut Section -->

                <!-- Full banner -->
                <div class="mb-8">
                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/shop.html"
                        class="d-block text-gray-90">
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
                                            <em class="font-size-14 font-weight-light">GIÁ CHỈ TỪ</em>
                                            <div class="font-size-30 font-weight-bold text-lh-1">
                                                <sup class="">$</sup>299<sup class="">99</sup>
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
                        <ul
                            class="nav nav-pills mb-2 pt-3 pt-md-0 mb-0 border-top border-color-1 border-md-top-0 align-items-center font-size-15 font-size-15-md flex-nowrap flex-md-wrap overflow-auto overflow-md-visble">
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="text-gray-90 btn btn-outline-primary border-width-2 rounded-pill py-1 px-4 font-size-15 text-lh-19 font-size-15-md"
                                    href="#">Top 20</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#">Phones & Tablets</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#">Laptops & Computers</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#"> Video Cameras</a>
                            </li>
                        </ul>
                    </dv>
                    <div class="js-slick-carousel u-slick u-slick--gutters-1 overflow-hidden u-slick-overflow-visble pt-3 pb-6"
                        data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4">
                        <div class="js-slide">
                            <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Tablets</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet Air 3 WiFi 64GB
                                                            Gold</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$629,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img2.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Laptops & Computers</a>
                                                    </div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet White EliteBook
                                                            Revolve 810 G2</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$1 299,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 remove-divider">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img3.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Accesories</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Pendrive USB 3.0 Flash
                                                            64 GB</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$110,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="js-slide">
                            <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Tablets</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet Air 3 WiFi 64GB
                                                            Gold</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$629,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img2.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Laptops & Computers</a>
                                                    </div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet White EliteBook
                                                            Revolve 810 G2</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$1 299,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 remove-divider">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img3.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Accesories</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Pendrive USB 3.0 Flash
                                                            64 GB</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$110,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="js-slide">
                            <ul class="row list-unstyled products-group no-gutters mb-0 overflow-visible">
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Tablets</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet Air 3 WiFi 64GB
                                                            Gold</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$629,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 border-bottom border-md-bottom-0">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img2.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Laptops & Computers</a>
                                                    </div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Tablet White EliteBook
                                                            Revolve 810 G2</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$1 299,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0 remove-divider">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner p-md-3 row no-gutters">
                                            <div class="col col-lg-auto col-xl-5 col-wd-auto product-media-left">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="max-width-150 d-block"><img class="img-fluid"
                                                        src="fontend/assets/img/150X140/img3.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div
                                                class="col col-xl-7 col-wd product-item__body pl-2 pl-lg-3 pl-xl-0 pl-wd-3 mr-wd-1">
                                                <div class="mb-4 mb-xl-2 mb-wd-4">
                                                    <div class="mb-2"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                            class="font-size-12 text-gray-5">Accesories</a></div>
                                                    <h5 class="product-item__title"><a
                                                            href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="text-blue font-weight-bold">Pendrive USB 3.0 Flash
                                                            64 GB</a></h5>
                                                </div>
                                                <div class="flex-center-between mb-3">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$110,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-compare mr-1 font-size-15"></i>
                                                            Compare</a>
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                            class="text-gray-6 font-size-13"><i
                                                                class="ec ec-favorites mr-1 font-size-15"></i>
                                                            Wishlist</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Prodcut-cards-carousel -->
                <!-- Product-with-banner -->
                <div class="mb-8">
                    <dv
                        class=" d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                        <h3 class="section-title mb-0 pb-2 font-size-22">Laptops & Computers</h3>
                    </dv>
                    <div class="row">
                        <div class="col-auto">
                            <a href="https://transvelo.github.io/electro-html/2.0/html/shop/shop.html"
                                class="d-block">
                                <img class="img-fluid" src="fontend/assets/img/212X305/img2.jpg"
                                    alt="Image Description">
                            </a>
                        </div>
                        <div class="col">
                            <ul class="row list-unstyled products-group no-gutters">
                                <li class="col-6 col-md-4 col-wd-3 product-item">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                        class="font-size-12 text-gray-5">Speakers</a></div>
                                                <h5 class="mb-1 product-item__title"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="text-blue font-weight-bold">Wireless Audio System
                                                        Multiroom 360 degree Full base audio</a></h5>
                                                <div class="mb-2">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="d-block text-center"><img class="img-fluid"
                                                            src="fontend/assets/img/212X200/img1.jpg"
                                                            alt="Image Description"></a>
                                                </div>
                                                <div class="flex-center-between mb-1">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$685,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-favorites mr-1 font-size-15"></i>
                                                        Wishlist</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="col-6 col-md-4 col-wd-3 product-item">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                        class="font-size-12 text-gray-5">Speakers</a></div>
                                                <h5 class="mb-1 product-item__title"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="text-blue font-weight-bold">Tablet White EliteBook
                                                        Revolve 810 G2</a></h5>
                                                <div class="mb-2">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="d-block text-center"><img class="img-fluid"
                                                            src="fontend/assets/img/212X200/img2.jpg"
                                                            alt="Image Description"></a>
                                                </div>
                                                <div class="flex-center-between mb-1">
                                                    <div
                                                        class="prodcut-price d-flex align-items-center position-relative">
                                                        <ins
                                                            class="font-size-20 text-red text-decoration-none">$1999,00</ins>
                                                        <del
                                                            class="font-size-12 tex-gray-6 position-absolute bottom-100">$2
                                                            299,00</del>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-favorites mr-1 font-size-15"></i>
                                                        Wishlist</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li
                                    class="col-6 col-md-4 col-wd-3 product-item remove-divider-md-lg remove-divider-xl d-md-none d-xl-block">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                        class="font-size-12 text-gray-5">Speakers</a></div>
                                                <h5 class="mb-1 product-item__title"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="text-blue font-weight-bold">Purple Solo 2 Wireless</a>
                                                </h5>
                                                <div class="mb-2">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="d-block text-center"><img class="img-fluid"
                                                            src="fontend/assets/img/212X200/img3.jpg"
                                                            alt="Image Description"></a>
                                                </div>
                                                <div class="flex-center-between mb-1">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$685,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-favorites mr-1 font-size-15"></i>
                                                        Wishlist</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="col-6 col-md-4 col-wd-3 product-item d-xl-none d-wd-block remove-divider">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                        class="font-size-12 text-gray-5">Speakers</a></div>
                                                <h5 class="mb-1 product-item__title"><a
                                                        href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="text-blue font-weight-bold">Smartphone 6S 32GB LTE</a>
                                                </h5>
                                                <div class="mb-2">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="d-block text-center"><img class="img-fluid"
                                                            src="fontend/assets/img/212X200/img4.jpg"
                                                            alt="Image Description"></a>
                                                </div>
                                                <div class="flex-center-between mb-1">
                                                    <div class="prodcut-price">
                                                        <div class="text-gray-100">$685,00</div>
                                                    </div>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                            class="btn-add-cart btn-primary transition-3d-hover"><i
                                                                class="ec ec-add-to-cart"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-item__footer">
                                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                        class="text-gray-6 font-size-13"><i
                                                            class="ec ec-favorites mr-1 font-size-15"></i>
                                                        Wishlist</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Product-with-banner -->
                <!-- Banner 2 columns -->
                <div class="mb-8">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="https://transvelo.github.io/electro-html/2.0/html/shop/shop.html">
                                <img class="img-fluid" src="fontend/assets/img/536X150/img1.jpg"
                                    alt="Image Description">
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="https://transvelo.github.io/electro-html/2.0/html/shop/shop.html">
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
                        <h3 class="section-title mb-0 pb-2 font-size-22">Laptops & Computers</h3>
                        <ul
                            class="nav nav-pills mb-2 pt-3 pt-md-0 mb-0 border-top border-color-1 border-md-top-0 align-items-center font-size-15 font-size-15-md flex-nowrap flex-md-wrap overflow-auto overflow-md-visble">
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="text-gray-90 btn btn-outline-primary border-width-2 rounded-pill py-1 px-4 font-size-15 text-lh-19 font-size-15-md"
                                    href="#">Top 20</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#">Phones & Tablets</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#">Laptops & Computers</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-md-shrink-1">
                                <a class="nav-link text-gray-8" href="#"> Video Cameras</a>
                            </li>
                        </ul>
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
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img2.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img3.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img4.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img5.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img6.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img7.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-slide products-group">
                            <div class="product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-wd-4 p-2 p-md-3">
                                        <div class="product-item__body pb-xl-2">
                                            <div class="mb-2"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/product-categories-7-column-full-width.html"
                                                    class="font-size-12 text-gray-5">Speakers</a></div>
                                            <h5 class="mb-1 product-item__title"><a
                                                    href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="text-blue font-weight-bold">Wireless Audio System Multiroom
                                                    360 degree Full base audio</a></h5>
                                            <div class="mb-2">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                    class="d-block text-center"><img class="img-fluid"
                                                        src="fontend/assets/img/212X200/img1.jpg"
                                                        alt="Image Description"></a>
                                            </div>
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100">$685,00</div>
                                                </div>
                                                <div class="d-none d-xl-block prodcut-add-cart">
                                                    <a href="https://transvelo.github.io/electro-html/2.0/html/shop/single-product-fullwidth.html"
                                                        class="btn-add-cart btn-primary transition-3d-hover"><i
                                                            class="ec ec-add-to-cart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-item__footer">
                                            <div class="border-top pt-2 flex-center-between flex-wrap">
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-compare mr-1 font-size-15"></i> Compare</a>
                                                <a href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                                    class="text-gray-6 font-size-13"><i
                                                        class="ec ec-favorites mr-1 font-size-15"></i> Wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Laptops & Computers -->
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
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img1.png"
                            alt="Image Description">
                    </a>
                </div>
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img2.png"
                            alt="Image Description">
                    </a>
                </div>
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img3.png"
                            alt="Image Description">
                    </a>
                </div>
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img4.png"
                            alt="Image Description">
                    </a>
                </div>
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img5.png"
                            alt="Image Description">
                    </a>
                </div>
                <div class="js-slide">
                    <a href="#" class="link-hover__brand">
                        <img class="img-fluid m-auto max-height-50" src="fontend/assets/img/200X60/img6.png"
                            alt="Image Description">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Brand Carousel -->
</main>
