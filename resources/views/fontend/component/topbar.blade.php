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
                    <li class="list-inline-item u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <a href="{{ route('client.contacts.index') }}" class="u-header-topbar__nav-link"><i class="ec ec-map-pointer mr-1"></i> Vị trí cửa hàng</a>
                    </li>
                    <li class="list-inline-item u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <a href="{{ route('order.track') }}" class="u-header-topbar__nav-link"><i
                                class="ec ec-transport mr-1"></i> Theo dõi đơn hàng</a>
                    </li>
                    
                    <li class="list-inline-item u-header-topbar__nav-item u-header-topbar__nav-item-border">
                        <a id="sidebarNavToggler" href="javascript:;" class="u-header-topbar__nav-link">
                            @auth
                            <div class="dropdown">
                                <a id="userDropdown" href="javascript:;" class="dropdown-toggle d-flex align-items-center text-dark" role="button" data-toggle="dropdown">
                                    <i class="ec ec-user mr-1"></i> Xin chào, {{ Auth::user()->name }}
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('user.show') }}">Quản lý tài khoản</a>
                                    <a class="dropdown-item" href="{{ route('client.orders.index') }}">Quản lý đơn hàng</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                </div>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            @else
                            <div>
                                <i class="ec ec-user mr-1"></i>
                                <a class="text-black" href="{{route('login.form')}}">Đăng nhập</a>
                            </div>
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isLoggedIn = @json(Auth::check()); // Kiểm tra đăng nhập
        const userName = @json(Auth::user()->name ?? ''); // Lấy tên user nếu có
        const sidebarNavToggler = document.getElementById('sidebarNavToggler');

        if (sidebarNavToggler) {
            if (isLoggedIn) {
                sidebarNavToggler.innerHTML = `<i class="ec ec-user mr-1"></i> Xin chào, ${userName}`;
            } else {
                sidebarNavToggler.innerHTML = `<i class="ec ec-user mr-1"></i> <a href="{{ route('login.form') }}">Đăng nhập</a>`;
            }
        }
    });
</script> --}}
