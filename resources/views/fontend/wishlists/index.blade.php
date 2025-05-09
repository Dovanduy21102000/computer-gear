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
                            @php $product = $item->product; @endphp
                                <tr>

                                    <td class="align-middle">
                                        <form action="{{ route('wishlist.remove', $item->product_id) }}" method="POST"
                                            style="display: inline-block;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: none; border: none; color: red; font-size: 24px; cursor: pointer;">×</button>
                                        </form>
                                    </td>


                                    <td class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . ($item->product->thumbnail ?? 'default-image.jpg')) }}"
                                            alt="{{ $item->product->name }}"
                                            style="width: 70px; height: 70px; object-fit: contain;">
                                        <a href="{{ route('client.products.detail', $item->product->slug) }}" class="text-dark fw-semibold">
                                            {{ $item->product->name }}
                                        </a>
                                    </td>

                                    <td class="align-middle">
                                        {{ number_format($item->product->price_sale ?? $item->product->price, 0, ',', '.') }}₫
                                    </td>


                                    <td class="align-middle">
                                        @if ($products && $product->quantity > 0)
                                            <span class="text-success">Còn hàng</span>
                                        @else
                                            <span class="text-danger">Hết hàng</span>
                                        @endif
                                    </td>


                                    <td class="align-middle">
                                        @if ($product->quantity > 0)
                                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                    <i class="fa fa-shopping-cart me-1"></i> Thêm vào giỏ
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Không thể mua</span>
                                        @endif
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