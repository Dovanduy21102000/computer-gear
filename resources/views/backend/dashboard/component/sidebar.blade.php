<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('dashboard.index') }}">
                <i class="bi bi-bar-chart"></i>
                <span>Thống kê</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('coupons.index') }}">
                <i class="bi bi-layout-text-window-reverse"></i><span>Quản lý khuyến mại</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-cart"></i><span>Quản lý sản phẩm</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
                <li>
                    <a href="{{ route('products.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}">
                        <i class="bi bi-circle"></i><span>Quản lý danh mục</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#post-management" aria-expanded="false">
                <i class="bi bi-file-earmark-text"></i>
                <span>Quản lý bài viết</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="post-management" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('posts.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách bài viết</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('category_post.index') }}">
                        <i class="bi bi-circle"></i><span>Quản lý danh mục bài viết</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark"></i><span>Đơn hàng</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="orders-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('orders.index') }}">
                        <i class="bi bi-circle"></i><span> Quản lí đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.cancelTabs') }}">
                        <i class="bi bi-circle"></i><span>Đơn hàng bị huỷ</span>
                    </a>
                </li>
                
            </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('users.index') }}">
                <i class="bi bi-person"></i>
                <span>Quản lý thành viên</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('banners.index') }}">
                <i class="bi bi-aspect-ratio"></i>
                <span>Quản lý Banner</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('contacts.index') }}">
                <i class="bi bi-envelope"></i>
                <span>Quản lý liên hệ</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('brands.index') }}">
                <i class="bi bi-badge-tm"></i>
                <span>Quản lý thương hiệu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('comments.index') }}">
                <i class="bi bi-chat-left-text"></i>
                <span>Quản lý bình luận</span>
            </a>
        </li>
        

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#attributes-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="true">
                <i class="bi bi-tag"></i><span>Quản lý thuộc tính</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attributes-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
                <li>
                    <a href="{{ route('attributes.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách thuộc tính</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('attributevalues.index') }}">
                        <i class="bi bi-circle"></i><span>Quản lý giá trị thuộc tính</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside><!-- End Sidebar-->
