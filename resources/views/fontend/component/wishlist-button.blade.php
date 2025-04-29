<button class="wishlist-button {{ !empty($isActive) && $isActive ? 'active' : '' }}" data-product-id="{{ $productId }}"
    style="background: none; border: none; cursor: pointer;">
    <i class="fa{{ !empty($isActive) && $isActive ? 's' : 'r' }} fa-heart"
        style="color: {{ !empty($isActive) && $isActive ? 'red' : '#333' }}; font-size: 20px;"></i>
        <span class=" text-gray-6 font-size-13">Yêu thích</span>
</button>
