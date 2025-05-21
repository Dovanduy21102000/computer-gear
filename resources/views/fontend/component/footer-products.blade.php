@php
$hideOnPages = ['profile', 'account', 'cart', 'orders'];
@endphp
@if (!in_array(request()->path(), $hideOnPages))
    <div class="container d-none d-lg-block mb-3">
        <div class="row">
            <div class="col-wd-3 col-lg-4">
                <div class="widget-column">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Sản phẩm nổi bật</h3>
                    </div>
                    <ul class="list-unstyled products-group">
                        @foreach ($topViewedProducts as $product)
                            <li class="product-item product-item__list row no-gutters mb-6 remove-divider">
                                <div class="col-auto">
                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                        class="d-block width-75 text-center">
                                        <img class="img-fluid" src="{{ asset('storage/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}">
                                    </a>
                                </div>
                                <div class="col pl-4 d-flex flex-column">
                                    <h5 class="product-item__title mb-0">
                                        <a href="{{ route('client.products.detail', $product->slug) }}"
                                            class="text-blue font-weight-bold">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <div class="prodcut-price mt-auto">
                                        @if ($product->price_sale && $product->price_sale < $product->price)
                                            <div class="d-flex align-items-center">
                                                <span class="font-size-15 text-danger font-weight-bold">
                                                    {{ number_format($product->price_sale, 0, ',', '.') }}₫
                                                </span>
                                                <span class="font-size-13 text-muted ml-2"
                                                    style="text-decoration: line-through;">
                                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                                </span>
                                            </div>
                                        @else
                                            <div class="font-size-15">
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-wd-3 d-none d-wd-block">
                <a href="../shop/shop.html" class="d-block"><img class="img-fluid"
                        src="fontend/assets/img/330X360/img1.jpg" alt="Image Description"></a>
            </div>
        </div>
    </div>
@endif