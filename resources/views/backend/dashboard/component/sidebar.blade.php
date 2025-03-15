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
        
        <li class="nav-item">
            <a class="nav-link" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="true">
              <i class="bi bi-cart"></i><span>Quản lý sản phẩm</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav" style="">
              <li>
                <a href="{{route('products.index')}}">
                  <i class="bi bi-circle"></i><span>Danh sách sản phẩm</span>
                </a>
              </li>
              <li>
                <a href="{{route('categories.index')}}">
                  <i class="bi bi-circle"></i><span>Quản lý danh mục</span>
                </a>
              </li>
              <li>
                <a href="{{route('brands.index')}}">
                  <i class="bi bi-circle"></i><span>Quản lý thương hiệu</span>
                </a>
              </li>

              <li>
                <a href="{{route('attributes.index')}}">
                  <i class="bi bi-circle"></i><span>Quản lý thuộc tính</span>
                </a>
              </li>
              
              <li>
                <a href="{{route('attributevalues.index')}}">
                  <i class="bi bi-circle"></i><span>Danh sách giá trị thuộc tính</span>
                </a>
              </li>

              <li>
                <a href="#">
                  <i class="bi bi-circle"></i><span>Quản lý bình luận</span>
                </a>
              </li>
            </ul>
          </li>
    </ul>

</aside><!-- End Sidebar-->
