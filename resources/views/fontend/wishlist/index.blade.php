<main id="content" role="main" class="wishlist-page">
    <div class="container" style="padding: 10px; border-bottom: 1px solid #9d9c9c;">
        <div class="my-md-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sản phẩm yêu thích</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4 mb-10">
        <h1 class="text-center mb-5">Sản phẩm yêu thích</h1>

        @if ($wishlists->count())
            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wishlists as $item)
                            <tr>
                                <!-- Remove (X) -->
                                <td class="align-middle">
                                    <a href="{{ route('wishlist.remove', $item->id) }}" class="text-danger fs-4" title="Remove">
                                        &times;
                                    </a>
                                </td>

                                <!-- Product image + name -->
                                <td class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . ($item->product->thumbnail ?? 'default-image.jpg')) }}"
                                        alt="{{ $item->product->name }}"
                                        style="width: 70px; height: 70px; object-fit: contain;">
                                    <a href="#" class="text-dark fw-semibold">{{ $item->product->name }}</a>
                                </td>

                                <!-- Price -->
                                <td class="align-middle">
                                    {{ number_format($item->product->price_sale ?? $item->product->price, 0, ',', '.') }}₫
                                </td>

                                <!-- Stock Status -->
                                <td class="align-middle">
                                    <span class="text-success">Còn hàng</span>
                                </td>

                                <!-- Add to Cart -->
                                <td class="align-middle">
                                    <button class="btn btn-light border rounded-pill px-4" disabled>
                                        Thêm vào giỏ hàng
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning text-center mt-5">
                Bạn chưa thêm sản phẩm nào vào danh sách yêu thích.
            </div>
        @endif
    </div>
</main>