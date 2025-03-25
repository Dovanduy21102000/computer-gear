<!-- Topbar -->
<div class="u-header-topbar py-2 d-none d-xl-block">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="topbar-left">
                <a href="#" class="text-gray-110 font-size-13 u-header-topbar__nav-link">Chào mừng tới Computer
                    Gear</a>
            </div>
            <div class="topbar-right ml-auto">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <a href="#" class="u-header-topbar__nav-link"><i class="ec ec-map-pointer mr-1"></i> Vị trí
                            cửa hàng</a>
                    </li>
                    <li class="list-inline-item mr-0 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <a href="https://transvelo.github.io/electro-html/2.0/html/shop/track-your-order.html"
                            class="u-header-topbar__nav-link"><i class="ec ec-transport mr-1"></i> Theo dõi đơn hàng của
                            bạn</a>
                    <li class="list-inline-item mr-2 u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <!-- Account Sidebar Toggle Button -->
                        <a id="profileDropdown" href="javascript:;" role="button" class="u-header-topbar__nav-link"
                            aria-controls="sidebarContent" aria-haspopup="true" aria-expanded="false"
                            data-unfold-event="click" data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarContent" data-unfold-type="css-animation"
                            data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight"
                            data-unfold-duration="500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            aria-controls="profileDropdownMenu">
                            @auth
                                <!-- Khi người dùng đã đăng nhập -->

                                <div class="dropdown">
                                    <a id="userDropdown" href="javascript:;"
                                        class="dropdown-toggle d-flex align-items-center" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ec ec-user mr-1"></i> Xin chào, {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="#">Quản lý tài khoản</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
                                            xuất</a>
                                    </div>
                                </div>

                                <!-- Form đăng xuất -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <!-- Khi người dùng chưa đăng nhập -->
                                <div id="topbar">
                                    <i class="ec ec-user mr-1"></i>
                                    <span onclick="toggleForm('login')">Đăng nhập</span>
                                </div>
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Topbar -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra trạng thái đăng nhập
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }}; // Laravel kiểm tra đăng nhập

        const sidebarNavToggler = document.getElementById('sidebarNavToggler');

        if (isLoggedIn) {
            // Chỉ truy cập 'name' khi người dùng đã đăng nhập
            const userName = "{{ Auth::user()->name ?? '' }}"; // Lấy tên người dùng nếu đã đăng nhập
            sidebarNavToggler.innerHTML = '<i class="ec ec-user mr-1"></i> Xin chào, ' + userName;
        } else {
            sidebarNavToggler.innerHTML =
                '<i class="ec ec-user mr-1"></i> Đăng ký<span class="text-gray-50">hoặc</span> Đăng nhập';
        }
    });
</script>
