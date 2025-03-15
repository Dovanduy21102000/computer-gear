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
            <a class="nav-link collapsed"  href="{{ route('coupons.index') }}">
                <i class="bi bi-layout-text-window-reverse"></i><span>Quản lý khuyến mại</span>
            </a>
            
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
