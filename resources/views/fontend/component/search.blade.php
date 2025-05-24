<div class="d-none d-xl-block bg-primary">
    <div class="container">
        <div class="row align-items-stretch min-height-50">
            <!-- Vertical Menu -->
            
            <!-- End Vertical Menu -->
            <!-- Search bar -->
            <div class="col align-self-center">
                <!-- Search-Form -->
                <form action="{{ route('search') }}" method="GET" class="js-focus-state">
                    <label class="sr-only" for="searchProduct">Tìm kiếm sản phẩm</label>
                    <div class="input-group">
                        <input type="text"
                            class="form-control py-2 pl-5 font-size-15 border-0 height-40 rounded-left-pill"
                            name="query" id="searchProduct" placeholder="Tìm kiếm sản phẩm"
                            aria-label="Search for Products" aria-describedby="searchProduct1" required>

                        <div class="input-group-append">
                            <button class="btn btn-dark height-40 py-2 px-3 rounded-right-pill" type="submit">
                                <span class="ec ec-search font-size-24"></span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- End Search-Form -->


            </div>
            <!-- End Search bar -->
            <!-- Header Icons -->
            <div class="col-md-auto align-self-center">
                <div class="d-flex">
                    <ul class="d-flex list-unstyled mb-0">

                        <li class="col"><a href="{{ route('wishlist.index') }}" class="text-gray-90"
                                data-toggle="tooltip" data-placement="top" title="Yêu thích"><i
                                    class="font-size-22 ec ec-favorites"></i></a></li>
                        <li class="col pr-0">
                            <meta name="user-id" content="{{ auth()->id() }}">

                            <a href="{{ route('cart.index') }}" class="text-gray-90 position-relative d-flex "
                                data-toggle="tooltip" data-placement="top" title="Giỏ hàng">
                                <i class="font-size-22 ec ec-shopping-bag"></i>
                                <span id="cart-badge-count"
                                    class="width-22 height-22 bg-dark position-absolute flex-content-center text-white rounded-circle left-12 top-8 font-weight-bold font-size-12">
                                    {{ $total_items }}
                                </span>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Header Icons -->
        </div>
    </div>
</div>
<!-- End Vertical-and-secondary-menu -->
