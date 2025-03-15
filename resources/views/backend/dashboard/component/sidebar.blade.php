<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="index.html">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

  <li class="nav-item">
    <a class="nav-link" href="{{ route('comment.index') }}">
      <i class="bi bi-file-earmark"></i>
      <span>Quản lý bình luận</span>
    </a>
  </li><!-- End Components Nav -->
  <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-file-earmark"></i><span>Quản lý thuộc tính</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{route('attributes.index')}}">
          <i class="bi bi-circle"></i><span> Danh sách thuộc tính</span>
        </a>
      </li>
      <li>
        <a href="{{route('attribute-values.index')}}">
          <i class="bi bi-circle"></i><span>Giá trị thuộc tính</span>
        </a>
      </li>
    </ul>
  </li>
  </ul>

</aside><!-- End Sidebar-->