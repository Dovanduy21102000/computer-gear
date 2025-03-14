<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="index.html">
                <i class="bi bi-grid"></i>
                <span>Thống kê</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#promo-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Quản lý khuyến mại</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="promo-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('coupons.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách khuyến mại</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('coupons.create') }}">
                        <i class="bi bi-circle"></i><span>Thêm khuyến mại</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('banners.index') }}">
                <i class="bi bi-aspect-ratio"></i>
                <span>Quản lý Banner</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('users.index') }}">
                <i class="bi bi-person"></i>
                <span>Quản lý thành viên</span>
            </a>
        </li>    
    </ul>

</aside><!-- End Sidebar-->
