<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="{{ route('home.index') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="{{ route('client.products.index') }}">Sản phẩm</a>
                        </li>

                        @if (isset($category) && $category)
                            @if ($category->parent)
                                <!-- Nếu danh mục có danh mục cha, hiển thị danh mục cha -->
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                                    <a
                                        href="{{ route('client.products.category', ['slug' => $category->parent->slug]) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                </li>
                            @endif

                            <!-- Hiển thị danh mục hiện tại -->
                            <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">
                                {{ $category->name }}
                            </li>
                        @else
                            <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">
                                Tất cả sản phẩm
                            </li>
                        @endif

                    </ol>
                </nav>

            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="row mb-8">
            <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                <div class="mb-8 border border-width-2 border-color-3 borders-radius-6">
                    <!-- List -->
                    <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
                        <li>
                            <a class="dropdown-toggle dropdown-toggle-collapse dropdown-title" href="javascript:;"
                                role="button" data-toggle="collapse" aria-expanded="false"
                                aria-controls="sidebarNav1Collapse" data-target="#sidebarNav1Collapse">
                                Tất cả danh mục
                            </a>

                            <div id="sidebarNav1Collapse" class="collapse" data-parent="#sidebarNav">
                                <ul id="sidebarNav1" class="list-unstyled dropdown-list">
                                    <!-- Danh mục -->
                                    @foreach ($categories as $category)
                                        <li>
                                            @php
                                                $query = request()->all();
                                                $query['category'] = $category->slug;
                                            @endphp
                                            <a class="dropdown-item"
                                                href="{{ route('client.products.filter', $query) }}">
                                                {{ $category->name }}
                                                <span class="text-gray-25 font-size-12 font-weight-normal">
                                                    ({{ $category->products()->count() }})
                                                </span>
                                            </a>

                                            @if ($category->children->count())
                                                <ul class="list-unstyled dropdown-list">
                                                    @foreach ($category->children as $child)
                                                        @php
                                                            $query = request()->all();
                                                            $query['category'] = $child->slug;
                                                        @endphp
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('client.products.filter', $query) }}">
                                                                {{ $child->name }}
                                                                <span
                                                                    class="text-gray-25 font-size-12 font-weight-normal">
                                                                    ({{ $child->products()->count() }})
                                                                </span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach

                                </ul>
                            </div>

                        </li>
                    </ul>
                    <!-- End List -->

                </div>
                <div class="mb-6">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Bộ lọc</h3>
                    </div>
                    <form id="filterForm" method="GET" action="{{ route('client.products.filter') }}">
                        @if (request()->has('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <div class="border-bottom pb-4 mb-4">
                            <h4 class="font-size-14 mb-3 font-weight-bold">Thương hiệu</h4>

                            @foreach ($brands as $brand)
                                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input brand-filter"
                                            id="brand{{ $brand->id }}" name="brand[]" value="{{ $brand->id }}"
                                            {{ in_array($brand->id, (array) request('brand', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="brand{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Bỏ nút submit --}}
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const brandCheckboxes = document.querySelectorAll('.brand-filter');
                            brandCheckboxes.forEach(function(checkbox) {
                                checkbox.addEventListener('change', function() {
                                    this.closest('form').submit();
                                });
                            });
                        });
                    </script>
                </div>





            </div>
            <div class="col-xl-9 col-wd-9gdot5">


                <!-- Shop-control-bar -->
                <div class="bg-gray-1 flex-center-between borders-radius-9 py-1">
                    <div class="d-xl-none">
                        <!-- Account Sidebar Toggle Button -->
                        <a id="sidebarNavToggler1" class="btn btn-sm py-1 font-weight-normal" href="javascript:;"
                            role="button" aria-controls="sidebarContent1" aria-haspopup="true" aria-expanded="false"
                            data-unfold-event="click" data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarContent1" data-unfold-type="css-animation"
                            data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft"
                            data-unfold-duration="500">
                            <i class="fas fa-sliders-h"></i> <span class="ml-1">Filters</span>
                        </a>
                        <!-- End Account Sidebar Toggle Button -->
                    </div>
                    <div class="px-3 d-none d-xl-block">
                        <ul class="nav nav-tab-shop" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-one-example1-tab" data-toggle="pill"
                                    href="#pills-one-example1" role="tab" aria-controls="pills-one-example1"
                                    aria-selected="false">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        <i class="fa fa-th"></i>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="pills-three-example1-tab" data-toggle="pill"
                                    href="#pills-three-example1" role="tab" aria-controls="pills-three-example1"
                                    aria-selected="true">
                                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                                        <i class="fa fa-list"></i>
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="d-flex">
                        <select id="sortSelect"
                            class="js-select selectpicker dropdown-select max-width-200 max-width-160-sm right-dropdown-0 px-2 px-xl-0"
                            data-style="btn-sm bg-white font-weight-normal py-2 border text-gray-20 bg-lg-down-transparent border-lg-down-0">
                            <option value="default" selected>Sắp xếp mặc định</option>
                            <option value="newest">Sắp xếp theo mới nhất</option>
                            <option value="price_asc">Sắp xếp từ thấp tới cao</option>
                            <option value="price_desc">Sắp xếp từ cao tới thấp</option>
                        </select>


                    </div>
                    <nav class="px-3 flex-horizontal-center text-gray-20">
                        @if ($products->count())
                            <span>Trang {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                        @endif
                    </nav>

                </div>
                <!-- End Shop-control-bar -->
                <!-- Shop Body -->
                <!-- Tab Content -->
                <div class="tab-content" id="pills-tabContent">
                    <!-- Grid View -->
                    <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel"
                        aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                        <ul class="row list-unstyled products-group no-gutters">
                            @foreach ($products as $product)
                                <li class="col-6 col-md-3 col-wd-2gdot4 product-item"
                                    data-created-at="{{ $product->created_at }}">
                                    <div class="product-item__outer h-100">
                                        <div class="product-item__inner px-xl-4 p-3">
                                            <div class="product-item__body pb-xl-2">
                                                <div class="mb-2">
                                                    <a href="{{ route('client.products.category', ['slug' => $product->category->slug]) }}"
                                                        class="font-size-12 text-gray-5">{{ $product->category->name ?? 'Danh mục' }}</a>
                                                </div>
                                                <h5 class="mb-1 product-item__title">
                                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                                        class="text-blue font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </h5>
                                                <div class="mb-2">
                                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                                        class="d-block text-center">
                                                        <img class="img-fluid w-100"
                                                            style="height: 150px; object-fit: cover;"
                                                            src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}">
                                                    </a>
                                                </div>
                                                <div class="flex-center-between mb-1">
                                                    <div class="prodcut-price">
                                                        @if ($product->price_sale)
                                                            <div class="text-danger fw-bold fs-5">
                                                                {{ number_format($product->price_sale, 0, ',', '.') }}đ
                                                            </div>
                                                            <div>
                                                                <del class="text-muted fw-semibold fs-6 me-2">
                                                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                                                </del>
                                                                <span class="badge bg-danger text-white fs-6 fw-bold">
                                                                    -{{ round((1 - $product->price_sale / $product->price) * 100) }}%
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="text-dark fw-bold fs-5">
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
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
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
                                </li>
                            @endforeach




                        </ul>


                        <!-- Hiển thị phân trang -->
                        <div class="pagination-container d-flex justify-content-center">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <!-- List View -->
                    <!-- List View -->
                    <div class="tab-pane fade pt-2" id="pills-three-example1" role="tabpanel"
                        aria-labelledby="pills-three-example1-tab" data-target-group="groups">
                        <ul class="d-block list-unstyled products-group prodcut-list-view">
                            @foreach ($products as $product)
                                <li class="product-item remove-divider">
                                    <div class="product-item__outer w-100">
                                        <div class="product-item__inner remove-prodcut-hover py-4 row">
                                            <div class="product-item__header col-6 col-md-4">
                                                <div class="mb-2">
                                                    <a href="{{ route('client.products.detail', $product->slug) }}"
                                                        class="d-block text-center">
                                                        <img class="img-fluid"
                                                            src="{{ asset('storage/' . $product->thumbnail) }}"
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
                                                        @if ($product->price_sale)
                                                            <div class="text-danger font-weight-bold">
                                                                {{ number_format($product->price_sale, 0, ',', '.') }}đ
                                                            </div>
                                                            <del
                                                                class="text-muted">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                                        @else
                                                            <div class="text-gray-100 font-weight-bold">
                                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <ul class="font-size-12 p-0 text-gray-110 mb-4 d-none d-md-block">
                                                        <li class="line-clamp-1 mb-1 list-bullet">Chất lượng cao cấp
                                                        </li>
                                                        <li class="line-clamp-1 mb-1 list-bullet">Thiết kế bền bỉ,
                                                            chống sốc</li>
                                                        <li class="line-clamp-1 mb-1 list-bullet">Bảo hành chính hãng
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-item__footer col-md-3 d-md-block">
                                                <div class="mb-3 d-flex flex-column align-items-center text-center">
                                                    <!-- Giá sản phẩm -->
                                                    <div
                                                        class="prodcut-price mb-3 d-flex flex-column align-items-start">
                                                        @if ($product->price_sale)
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
                                                        @else
                                                            <div class="text-dark font-weight-bold">
                                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                                            </div>
                                                        @endif
                                                    </div>




                                                    <!-- Nút thêm vào giỏ hàng -->
                                                    <div class="d-none d-xl-block prodcut-add-cart w-100">
                                                        @if ($product->is_variant)
                                                            <a href="{{ route('client.products.detail', $product->slug) }}"
                                                                class="btn-add-cart btn-primary transition-3d-hover">
                                                                <i class="ec ec-add-to-cart"></i>
                                                            </a>
                                                        @else
                                                            <form action="{{ route('cart.add') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button
                                                                    class="btn btn-warning w-100 py-2 rounded-pill shadow-sm transition-3d-hover"
                                                                    type="submit"
                                                                    style="font-size: 1rem; font-weight: 600; background: #ffc107; border: none;">
                                                                    <i class="ec ec-add-to-cart mr-2"></i> Thêm vào giỏ
                                                                    hàng
                                                                </button>

                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-center align-items-center">
                                                    <a href="#"
                                                        class="text-gray-6 font-size-13 mx-wd-3 d-flex align-items-center">
                                                        <i class="ec ec-favorites mr-1 font-size-15"></i> Yêu thích
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Hiển thị phân trang -->
                        <div class="pagination-container d-flex justify-content-center">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sortSelect = document.getElementById("sortSelect");
        const productList = document.querySelector(".products-group");
        const originalProducts = Array.from(productList.children);

        sortSelect.addEventListener("change", function() {
            let products = [...originalProducts];
            let sortBy = sortSelect.value;

            if (sortBy !== "default") {
                products.sort((a, b) => {
                    let priceA = parseInt(a.querySelector(".prodcut-price div")?.textContent
                        .replace(/\D/g, "")) || 0;
                    let priceB = parseInt(b.querySelector(".prodcut-price div")?.textContent
                        .replace(/\D/g, "")) || 0;
                    let dateA = a.dataset.createdAt ? new Date(a.dataset.createdAt) : new Date(
                        0);
                    let dateB = b.dataset.createdAt ? new Date(b.dataset.createdAt) : new Date(
                        0);

                    if (sortBy === "price_asc") return priceA - priceB;
                    if (sortBy === "price_desc") return priceB - priceA;
                    if (sortBy === "newest") return dateB - dateA;
                    return 0;
                });
            }

            // Xóa & thêm lại sản phẩm theo thứ tự mới
            productList.replaceChildren(...products);
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        const sortSelect = document.getElementById("sortSelect");
        const productList = document.querySelector(".prodcut-list-view");
        const originalProducts = Array.from(productList.children); // Lưu trữ danh sách gốc

        sortSelect.addEventListener("change", function() {
            let products = [...originalProducts]; // Luôn khởi tạo từ danh sách gốc
            let sortBy = sortSelect.value;

            if (sortBy !== "default") {
                products.sort((a, b) => {
                    let priceA = parseInt(a.querySelector(".prodcut-price div")?.textContent
                        .replace(/\D/g, "")) || 0;
                    let priceB = parseInt(b.querySelector(".prodcut-price div")?.textContent
                        .replace(/\D/g, "")) || 0;
                    let dateA = a.dataset.createdAt ? new Date(a.dataset.createdAt) : new Date(
                        0);
                    let dateB = b.dataset.createdAt ? new Date(b.dataset.createdAt) : new Date(
                        0);

                    if (sortBy === "price_asc") return priceA - priceB;
                    if (sortBy === "price_desc") return priceB - priceA;
                    if (sortBy === "newest") return dateB - dateA;
                    return 0;
                });
            }

            // Xóa & thêm lại sản phẩm theo thứ tự mới
            productList.replaceChildren(...products);
        });
    });
</script>
