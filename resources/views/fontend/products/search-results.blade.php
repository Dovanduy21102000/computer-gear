<main class="container my-4">
    <h4 class="mb-3 fs-5 fw-bold text-secondary text-uppercase">Kết quả tìm kiếm cho "{{ $query }}"</h4>



    @if ($products->count())
        <div class="row">
            @foreach ($products as $product)
                <div class="col-6 col-md-4 col-lg-2 mb-4">

                    <div class="product-item__outer h-100" data-product-id="{{ $product->id }}">
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
                                <div class="flex-center-between mb-1">
                                    <div class="prodcut-price">
                                        @if ($product->price_sale)
                                            <div class="prodcut-price d-flex align-items-center position-relative">
                                                <ins
                                                    class="font-size-20 text-red text-decoration-none product-sale-price">{{ number_format($product->price_sale) }}đ</ins>
                                                <del
                                                    class="font-size-12 tex-gray-6 position-absolute bottom-100 product-price">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                            </div>
                                        @else
                                            <div class="text-dark fw-bold fs-5 product-price">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </div>
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
                </div>
            @endforeach
        </div>

        <!-- Hiển thị phân trang -->
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @else
        <p class="text-muted">Không tìm thấy sản phẩm nào.</p>
    @endif
</main>
