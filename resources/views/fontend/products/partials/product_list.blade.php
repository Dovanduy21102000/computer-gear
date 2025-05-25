<style>
    .product-name {
        display: block !important;
        max-width: 160px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .product-item {
        margin-bottom: 24px !important;
    }
</style>
@php
    $view = $view ?? 'grid';
@endphp
@if ($view == 'grid')
    <div style="position: relative;">
        <div id="productGridSpinner"
            style="display:none; position:absolute; left:0; top:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; justify-content:center; align-items:center;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <ul class="row list-unstyled products-group no-gutters product-list">
            @foreach ($products as $product)
                @php
                    $hasVariant = $product->is_variant && $product->variants->count();
                    $variantSalePrices = $hasVariant
                        ? $product->variants->pluck('price_sale')->filter(function ($price) {
                            return is_numeric($price) && $price > 0;
                        })
                        : collect();
                    $variantBasePrices = $hasVariant
                        ? $product->variants->pluck('price')->filter(function ($price) {
                            return is_numeric($price) && $price > 0;
                        })
                        : collect();
                    if ($variantSalePrices->count()) {
                        $minPrice = $variantSalePrices->min();
                        $maxPrice = $variantSalePrices->max();
                        $isSale = true;
                        $originalMin = $variantBasePrices->min();
                        $originalMax = $variantBasePrices->max();
                    } else {
                        $minPrice = $variantBasePrices->min();
                        $maxPrice = $variantBasePrices->max();
                        $isSale = false;
                        $originalMin = null;
                        $originalMax = null;
                    }
                    $sortPrice = $hasVariant ? $minPrice : ($product->price_sale ?: $product->price);
                @endphp
                <li class="col-6 col-md-3 product-item" data-product-id="{{ $product->id }}"
                    data-created-at="{{ $product->created_at }}" data-price="{{ $sortPrice }}">
                    <div class="product-item__outer h-100">
                        <div class="product-item__inner px-xl-4 p-3">
                            <div class="product-item__body pb-xl-2">
                                <div class="mb-2">
                                    <a href="{{ $product->category?->slug ? route('client.products.category', ['slug' => $product->category->slug]) : '#' }}"
                                        class="font-size-12 text-gray-5">
                                        {{ $product->category->name ?? 'Danh mục' }}
                                    </a>
                                </div>
                                <h5 class="mb-1 product-item__title">
                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                        class="text-blue font-weight-bold product-name">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <div class="mb-2">
                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                        class="d-block text-center">
                                        <img class="img-fluid w-100" style="height: 150px; object-fit: cover;"
                                            src="{{ asset('storage/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}">
                                    </a>
                                </div>
                                <div class="flex-center-between mb-1 mt-4">
                                    <div class="prodcut-price">
                                        @if ($hasVariant && $minPrice)
                                            <span class="text-danger fw-bold">
                                                {{ number_format($minPrice, 0, ',', '.') }}₫@if ($minPrice != $maxPrice)
                                                    
                                                @endif
                                            </span>
                                            @if ($isSale && $originalMin)
                                                <br>
                                                <del class="text-muted">
                                                    {{ number_format($originalMin, 0, ',', '.') }}₫@if ($originalMin != $originalMax)
                                                
                                                    @endif
                                                </del>
                                            @endif
                                        @elseif ($hasVariant)
                                            <span class="text-danger fw-bold">Liên hệ</span>
                                        @elseif ($product->price_sale)
                                            <div class="prodcut-price d-flex align-items-center position-relative">
                                                <ins
                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($product->price_sale) }}đ</ins>
                                                <del
                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100 product-price">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                            </div>
                                        @elseif ($product->price > 0)
                                            <div class="text-dark fw-bold fs-5 product-price">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </div>
                                        @else
                                            <span class="text-danger fw-bold">Liên hệ</span>
                                        @endif
                                    </div>
                                    <div class="d-none d-xl-block prodcut-add-cart">
                                        @if ($product->is_variant)
                                            <a href="{{ route('client.products.detail', $product->slug) }}"
                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                <i class="ec ec-add-to-cart"></i>
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
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
                                    @include('fontend.component.wishlist-button', ['product' => $product])
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="pagination-container d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@elseif ($view == 'list')
    <ul class="d-block list-unstyled products-group prodcut-list-view">
        @foreach ($products as $product)
            @php
                $hasVariant = $product->is_variant && $product->variants->count();
                $variantSalePrices = $hasVariant
                    ? $product->variants->pluck('price_sale')->filter(function ($price) {
                        return is_numeric($price) && $price > 0;
                    })
                    : collect();
                $variantBasePrices = $hasVariant
                    ? $product->variants->pluck('price')->filter(function ($price) {
                        return is_numeric($price) && $price > 0;
                    })
                    : collect();
                if ($variantSalePrices->count()) {
                    $minPrice = $variantSalePrices->min();
                    $maxPrice = $variantSalePrices->max();
                    $isSale = true;
                    $originalMin = $variantBasePrices->min();
                    $originalMax = $variantBasePrices->max();
                } else {
                    $minPrice = $variantBasePrices->min();
                    $maxPrice = $variantBasePrices->max();
                    $isSale = false;
                    $originalMin = null;
                    $originalMax = null;
                }
                $sortPrice = $hasVariant ? $minPrice : ($product->price_sale ?: $product->price);
            @endphp
            <li class="product-item remove-divider">
                <div class="product-item__outer w-100">
                    <div class="product-item__inner remove-prodcut-hover py-4 row">
                        <div class="product-item__header col-6 col-md-4">
                            <div class="mb-2">
                                <a href="{{ route('client.products.detail', $product->slug) }}"
                                    class="d-block text-center">
                                    <img class="img-fluid" src="{{ asset('storage/' . $product->thumbnail) }}"
                                        alt="{{ $product->name }}">
                                </a>
                            </div>
                        </div>
                        <div class="product-item__body col-6 col-md-5">
                            <div class="pr-lg-10">
                                <div class="mb-2">
                                    <a href="#"
                                        class="font-size-12 text-gray-5">{{ $product->category->name ?? 'Danh mục' }}</a>
                                </div>
                                <h5 class="mb-2 product-item__title">
                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                        class="text-blue font-weight-bold">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <div class="prodcut-price mb-2">
                                    @if ($hasVariant && $minPrice)
                                        <span class="text-danger fw-bold">
                                            {{ number_format($minPrice, 0, ',', '.') }}₫
                                        </span>
                                        @if ($isSale && $originalMin)
                                            <br>
                                            <del class="text-muted">
                                                {{ number_format($originalMin, 0, ',', '.') }}₫
                                            </del>
                                        @endif
                                    @elseif ($hasVariant)
                                        <span class="text-danger fw-bold">Liên hệ</span>
                                    @elseif ($product->price_sale)
                                        <div class="prodcut-price d-flex align-items-center position-relative">
                                            <ins
                                                class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($product->price_sale) }}đ</ins>
                                            <del
                                                class="font-size-12 tex-gray-6 position-absolute bottom-100 product-price">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                        </div>
                                    @elseif ($product->price > 0)
                                        <div class="text-dark fw-bold fs-5 product-price">
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        </div>
                                    @else
                                        <span class="text-danger fw-bold">Liên hệ</span>
                                    @endif
                                </div>
                                <ul class="font-size-12 p-0 text-gray-110 mb-4 d-none d-md-block">
                                    <li class="line-clamp-1 mb-1 list-bullet">Chất lượng cao cấp</li>
                                    <li class="line-clamp-1 mb-1 list-bullet">Thiết kế bền bỉ, chống sốc</li>
                                    <li class="line-clamp-1 mb-1 list-bullet">Bảo hành chính hãng</li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-item__footer col-md-3 d-md-block">
                            <div class="mb-3 d-flex flex-column align-items-center text-center">
                                <div class="prodcut-price mb-3 d-flex flex-column align-items-start">
                                    @if ($hasVariant && $minPrice)
                                        <span class="text-danger fw-bold">
                                            {{ number_format($minPrice, 0, ',', '.') }}₫@if ($minPrice != $maxPrice)
                                                – {{ number_format($maxPrice, 0, ',', '.') }}₫
                                            @endif
                                        </span>
                                        @if ($isSale && $originalMin)
                                            <br>
                                            <del class="text-muted">
                                                {{ number_format($originalMin, 0, ',', '.') }}₫@if ($originalMin != $originalMax)
                                                    – {{ number_format($originalMax, 0, ',', '.') }}₫
                                                @endif
                                            </del>
                                        @endif
                                    @elseif ($hasVariant)
                                        <span class="text-danger fw-bold">Liên hệ</span>
                                    @elseif ($product->price_sale)
                                        <div class="text-danger font-weight-bold">
                                            {{ number_format($product->price_sale, 0, ',', '.') }}đ
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <del class="text-muted fw-semibold fs-5 me-2">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </del>
                                            <span class="badge bg-danger text-white fs-6 fw-bold">
                                                -{{ round((1 - $product->price_sale / $product->price) * 100) }}%
                                            </span>
                                        </div>
                                    @elseif ($product->price > 0)
                                        <div class="text-dark font-weight-bold">
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        </div>
                                    @else
                                        <span class="text-danger fw-bold">Liên hệ</span>
                                    @endif
                                </div>
                                <div class="d-none d-xl-block prodcut-add-cart w-100">
                                    @if ($product->is_variant)
                                        <a href="{{ route('client.products.detail', $product->slug) }}"
                                            class="btn btn-warning w-100 py-2 rounded-pill shadow-sm transition-3d-hover"
                                            type="submit"
                                            style="font-size: 1rem; font-weight: 600; background: #ffc107; border: none;">
                                            <i class="ec ec-add-to-cart mr-2"></i> Thêm vào giỏ hàng
                                        </a>
                                    @else
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button
                                                class="btn btn-warning w-100 py-2 rounded-pill shadow-sm transition-3d-hover"
                                                type="submit"
                                                style="font-size: 1rem; font-weight: 600; background: #ffc107; border: none;">
                                                <i class="ec ec-add-to-cart mr-2"></i> Thêm vào giỏ hàng
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" class="text-gray-6 font-size-13 mx-wd-3 d-flex align-items-center">
                                    <i class="ec ec-favorites mr-1 font-size-15"></i> Yêu thích
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="pagination-container d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@endif
