<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard.index') }}">
                <i class="bi bi-bar-chart"></i>
                <span>Thống kê</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('users.index') }}">
                <i class="bi bi-person"></i>
                <span>Quản lý thành viên</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('orders.index') }}">
                <i class="bi bi-card-list"></i>
                <span>Quản lý đơn hàng</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('contacts.index') }}">
                <i class="bi bi-envelope"></i>
                <span>Quản lý liên hệ</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="true">
                <i class="bi bi-cart"></i><span>Quản lý sản phẩm</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav" style="">

            <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">

                <li>
                    <a href="{{ route('products.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}">
                        <i class="bi bi-circle"></i><span>Quản lý danh mục sản phẩm</span>
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
            <a class="nav-link" data-bs-target="#attributes-nav" data-bs-toggle="collapse" href="#"
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
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('posts.index') }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Quản lý bài viết</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('brands.index') }}">
                <i class="bi bi-badge-tm"></i>
                <span>Quản lý thương hiệu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('banners.index') }}">
                <i class="bi bi-aspect-ratio"></i>
                <span>Quản lý Banner</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('banners.index') }}">
                <i class="bi bi-chat-left-text"></i>
                <span>Quản lý bình luận</span>
            </a>
        </li>






    </ul>
</aside><!-- End Sidebar-->
