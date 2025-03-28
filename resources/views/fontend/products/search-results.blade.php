<main class="container my-4">
    <h4 class="mb-3 fs-5 fw-bold text-secondary text-uppercase">Kết quả tìm kiếm cho "{{ $query }}"</h4>



    @if ($products->count())
        <div class="row">
            @foreach ($products as $product)
              <div class="col-6 col-md-4 col-lg-2 mb-4">

                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('client.products.detail', $product->slug) }}">
                            <div class="ratio ratio-4x3">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top img-fluid"
                                    alt="{{ $product->name }}" style="object-fit: cover;">
                            </div>
                        </a>
                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{ route('client.products.detail', $product->slug) }}" class="text-dark">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="mb-1 text-muted small">
                                <a href="{{ route('client.products.category', $product->category->slug ?? '#') }}">
                                    {{ $product->category->name ?? 'Danh mục' }}
                                </a>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if ($product->price_sale)
                                        <span
                                            class="text-danger font-weight-bold">{{ number_format($product->price_sale, 0, ',', '.') }}
                                            đ</span>
                                        <br>
                                        <del class="text-muted small">{{ number_format($product->price, 0, ',', '.') }}
                                            đ</del>
                                    @else
                                        <span
                                            class="text-dark font-weight-bold">{{ number_format($product->price, 0, ',', '.') }}
                                            đ</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
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
