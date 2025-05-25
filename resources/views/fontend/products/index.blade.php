<!-- ========== MAIN CONTENT ========== -->
<style>
    .new-product-title,
    .new-product-title a {
        display: block !important;
        max-width: 120px !important;
        /* Adjust as needed for your layout */
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>
<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                        </li>

                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1">
                            <a href="{{ route('client.products.index') }}">Sản phẩm</a>
                        </li>


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
                                <ul id="sidebarNav" class="list-unstyled mb-0">
                                    <li class="nav-item">
                                        <a class="dropdown-toggle text-uppercase font-weight-bold d-block p-3 bg-light rounded"
                                            href="javascript:;" role="button" data-toggle="collapse"
                                            aria-expanded="false" aria-controls="sidebarNav1Collapse"
                                            data-target="#sidebarNav1Collapse">
                                            <i class="bi bi-list"></i> Tất cả danh mục
                                        </a>

                                        <div id="sidebarNav1Collapse" class="collapse" data-parent="#sidebarNav">
                                            <ul id="sidebarNav1" class="list-unstyled pl-3">
                                                <!-- Danh mục -->
                                                @foreach ($categories as $category)
                                                    <li class="nav-item">
                                                        @php
                                                            $query = request()->all();
                                                            $query['category'] = $category->slug;
                                                        @endphp
                                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                            href="{{ route('client.products.filter', $query) }}">
                                                            <span>{{ $category->name }}</span>
                                                            <span class="badge badge-pill badge-secondary">
                                                                @php
                                                                    $totalProducts = $category->products()->count();
                                                                    foreach ($category->children as $child) {
                                                                        $totalProducts += $child->products()->count();
                                                                    }
                                                                @endphp
                                                                {{ $totalProducts }}
                                                            </span>
                                                        </a>

                                                        @if ($category->children->count())
                                                            <ul class="list-unstyled pl-3">
                                                                @foreach ($category->children as $child)
                                                                    @php
                                                                        $query = request()->all();
                                                                        $query['category'] = $child->slug;
                                                                    @endphp
                                                                    <li class="nav-item">
                                                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                                            href="{{ route('client.products.filter', $query) }}">
                                                                            <span>{{ $child->name }}</span>
                                                                            <span
                                                                                class="badge badge-pill badge-secondary">
                                                                                {{ $child->products()->count() }}
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
                <div class="mb-8">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">
                            Sản phẩm mới nhất
                        </h3>
                    </div>
                    <ul class="list-unstyled">
                        @foreach ($newProduct as $product)
                            <li class="mb-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <a href="{{ route('client.products.detail', $product->slug) }}"
                                            class="d-block width-75">
                                            <img class="img-fluid" src="{{ asset('storage/' . $product->thumbnail) }}"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h3 class="text-lh-1dot2 font-size-14 mb-0 new-product-title">
                                            <a href="{{ route('client.products.detail', $product->slug) }}"
                                                title="{{ $product->name }}">
                                                {{ Str::limit($product->name, 22) }}
                                            </a>
                                        </h3>
                                        <div class="text-warning text-ls-n2 font-size-16 mb-1" style="width: 80px;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <small
                                                    class="{{ $i <= $product->rating ? 'fas' : 'far' }} fa-star {{ $i > $product->rating ? 'text-muted' : '' }}"></small>
                                            @endfor
                                        </div>
                                        <div class="font-weight-bold">
                                            @php
                                                $hasVariant = $product->is_variant && $product->variants->count();
                                                $variantSalePrices = $hasVariant
                                                    ? $product->variants->pluck('price_sale')->filter()
                                                    : collect();
                                                $variantBasePrices = $hasVariant
                                                    ? $product->variants->pluck('price')
                                                    : collect();
                                                if ($variantSalePrices->count()) {
                                                    $minPrice = $variantSalePrices->min();
                                                    $isSale = true;
                                                    $originalMin = $variantBasePrices->min();
                                                } else {
                                                    $minPrice = $variantBasePrices->min();
                                                    $isSale = false;
                                                    $originalMin = null;
                                                }
                                            @endphp
                                            @if ($hasVariant)
                                                <span class="text-danger fw-bold">
                                                    {{ number_format($minPrice, 0, ',', '.') }}₫
                                                </span>
                                                @if ($isSale)
                                                    <br>
                                                    <del class="text-muted">
                                                        {{ number_format($originalMin, 0, ',', '.') }}₫
                                                    </del>
                                                @endif
                                            @elseif ($product->price_sale)
                                                <del
                                                    class="text-muted">{{ number_format($product->price, 0, ',', '.') }}₫</del>
                                                <span
                                                    class="text-danger">{{ number_format($product->price_sale, 0, ',', '.') }}₫</span>
                                            @else
                                                {{ number_format($product->price, 0, ',', '.') }}₫
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <div class="col-xl-9 col-wd-9gdot5">


                <!-- Shop-control-bar -->
                <div class="bg-gray-1 flex-center-between borders-radius-9 py-1">
                    <div class="d-xl-none">
                        <!-- Account Sidebar Toggle Button -->
                        <a id="sidebarNavToggler1" class="btn btn-sm py-1 font-weight-normal" href="javascript:;"
                            role="button" aria-controls="sidebarContent1" aria-haspopup="true"
                            aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false"
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
                            <option value="mac-dinh" @if (($sortSlug ?? '') == 'mac-dinh' || empty($sortSlug)) selected @endif>Sắp xếp mặc định
                            </option>
                            <option value="moi-nhat" @if (($sortSlug ?? '') == 'moi-nhat') selected @endif>Sắp xếp theo mới
                                nhất</option>
                            <option value="gia-thap-nhat" @if (($sortSlug ?? '') == 'gia-thap-nhat') selected @endif>Sắp xếp từ
                                thấp tới cao</option>
                            <option value="gia-cao-nhat" @if (($sortSlug ?? '') == 'gia-cao-nhat') selected @endif>Sắp xếp từ
                                cao tới thấp</option>
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
                        <div id="ajaxProductListWrapper">
                            <div id="ajaxProductListSpinner">
                                <div class="spinner-border text-primary" role="status"><span
                                        class="sr-only">Loading...</span></div>
                            </div>
                            <div id="ajaxProductList">
                                <ul class="row product-list">
                                @include('fontend.products.partials.product_list', [
                                    'products' => $products,
                                ])
                                </ul>
                            </div>
                        </div>
                    </div>


                    <!-- List View -->
                    <!-- List View -->
                    <div class="tab-pane fade pt-2" id="pills-three-example1" role="tabpanel"
                        aria-labelledby="pills-three-example1-tab" data-target-group="groups">
                        <div id="ajaxProductListWrapper">
                            <div id="ajaxProductListSpinner">
                                <div class="spinner-border text-primary" role="status"><span
                                        class="sr-only">Loading...</span></div>
                            </div>
                            <div id="ajaxProductList">
                                @include('fontend.products.partials.product_list', [
                                    'products' => $products,
                                    'view' => 'list',
                                ])
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<style>
    #ajaxProductListSpinner {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        z-index: 100;
        justify-content: center;
        align-items: center;
    }

    #ajaxProductListSpinner .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    #ajaxProductListWrapper {
        position: relative;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var sortSelect = document.getElementById("sortSelect");
        var spinner = document.getElementById("ajaxProductListSpinner");
        if (sortSelect) {
            sortSelect.addEventListener("change", function() {
                var sortValue = sortSelect.value;
                var url = new URL(window.location.href);
                var basePath = '/products';
                if (sortValue !== 'mac-dinh') {
                    basePath += '/sort/' + sortValue;
                }
                var params = url.searchParams.toString();
                var newUrl = basePath + (params ? ('?' + params) : '');
                if (spinner) spinner.style.display = 'flex';
                fetch(newUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        var newHtml = document.createElement('div');
                        newHtml.innerHTML = data;
                        var newAjaxProductList = newHtml.querySelector('#ajaxProductList');
                        if (newAjaxProductList) {
                            document.querySelectorAll('#ajaxProductList').forEach(function(el) {
                                el.innerHTML = newAjaxProductList.innerHTML;
                            });
                        }
                        history.pushState(null, '', newUrl);
                    })
                    .finally(function() {
                        if (spinner) spinner.style.display = 'none';
                    });
            });
        }
    });
</script>
