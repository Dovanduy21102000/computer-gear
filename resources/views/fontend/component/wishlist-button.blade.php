@php
    $product = $product ?? null;
@endphp

<a href="javascript:void(0);" class="wishlist-toggle text-gray-6 font-size-13 mx-wd-3 d-flex align-items-center"
    data-product-id="{{ $product->id }}">
    <i class="ec ec-favorites wishlist-icon me-1 {{ $product->isInWishlist() ? 'active' : '' }}"
        data-product-id="{{ $product->id }}"></i>
    <span>Yêu thích</span>
</a>