<main id="content" role="main" class="wishlist-page">
    <div class="container bg-md-transparent" style="padding: 10px; border-bottom: 1px solid #9d9c9c;">
        <div class="container">
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách yêu thích</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <style>
        .wishlist-table {
            background-color: #FFF6DC;
            border: 1.2px solid #D9B867;
            border-radius: 6px;
        }

        .wishlist-table th {
            background-color: #F8D472;
            color: #3D3D3D;
            padding: 12px;
        }

        .wishlist-table td {
            border-top: 1px solid #D9B867;
            color: #3D3D3D;
            padding: 10px;
        }

        .btn-gold {
            background-color: #F8D472;
            color: #3D3D3D;
            border: none;
            border-radius: 5px;
        }
    </style>

    <div class="container mt-4 mb-10">
        <h1 class="text-center">Sản phẩm yêu thích</h1>

        @if ($wishlists->count())
            <table class="table wishlist-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wishlists as $item)
                        <tr>
                            <td style="max-width: 100px;">
                                <img src="{{ asset('storage/' . ($item->product->thumbnail ?? 'default-image.jpg')) }}"
                                    alt="{{ $item->product->name }}" class="img-fluid">
                            </td>
                            <td>
                                <a href="#" class="text-dark">{{ $item->product->name }}</a>
                            </td>
                            <td>
                                <span>{{ number_format($item->product->price_sale ?? $item->product->price, 0, ',', '.') }}₫</span>
                            </td>
                            <td>
                                <a href="{{ route('wishlist.remove', $item->id) }}"
                                    class="btn btn-sm btn-outline-danger">Xóa</a>
                                <a href="{{ route('product.show', $item->product->slug) }}" class="btn btn-sm btn-gold">Xem sản
                                    phẩm</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning text-center mt-5">
                Bạn chưa thêm sản phẩm nào vào danh sách yêu thích.
            </div>
        @endif
    </div>
</main>