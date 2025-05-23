 <!-- Logo and Menu -->
 <div class="py-2 py-xl-4 bg-primary-down-lg">
     <div class="container my-0dot5 my-xl-0">
         <div class="row align-items-center">
             <!-- Logo-offcanvas-menu -->
             <div class="col-auto">
                 <!-- Nav -->
                 <nav
                     class="navbar navbar-expand u-header__navbar py-0 justify-content-xl-between max-width-270 min-width-270">
                     <!-- Logo -->
                     <a class="order-1 order-xl-0 navbar-brand u-header__navbar-brand u-header__navbar-brand-center"
                         href="{{ route('home.index') }}" aria-label="Computer Gear">
                         <img src="{{ asset('fontend/assets/img/logo-transparent.png') }}" alt="computergear">
                     </a>
                     <!-- End Logo -->


                     <!-- Fullscreen Toggle Button -->
                     <button id="sidebarHeaderInvokerMenu" type="button"
                         class="navbar-toggler d-block btn u-hamburger mr-3 mr-xl-0" aria-controls="sidebarHeader"
                         aria-haspopup="true" aria-expanded="false" data-unfold-event="click"
                         data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarHeader1"
                         data-unfold-type="css-animation" data-unfold-animation-in="fadeInLeft"
                         data-unfold-animation-out="fadeOutLeft" data-unfold-duration="500">
                         <span id="hamburgerTriggerMenu" class="u-hamburger__box">
                             <span class="u-hamburger__inner"></span>
                         </span>
                     </button>
                     <!-- End Fullscreen Toggle Button -->
                 </nav>
                 <!-- End Nav -->

                 <!-- ========== HEADER SIDEBAR ========== -->
                 <aside id="sidebarHeader1" class="u-sidebar u-sidebar--left"
                     aria-labelledby="sidebarHeaderInvokerMenu">
                     <div class="u-sidebar__scroller">
                         <div class="u-sidebar__container">
                             <div class="u-header-sidebar__footer-offset pb-0">
                                 <!-- Toggle Button -->
                                 <div class="position-absolute top-0 right-0 z-index-2 pt-4 pr-7">
                                     <button type="button" class="close ml-auto" aria-controls="sidebarHeader"
                                         aria-haspopup="true" aria-expanded="false" data-unfold-event="click"
                                         data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarHeader1"
                                         data-unfold-type="css-animation" data-unfold-animation-in="fadeInLeft"
                                         data-unfold-animation-out="fadeOutLeft" data-unfold-duration="500">
                                         <span aria-hidden="true"><i
                                                 class="ec ec-close-remove text-gray-90 font-size-20"></i></span>
                                     </button>
                                 </div>
                                 <!-- End Toggle Button -->

                                 <!-- Content -->
                                 <div class="js-scrollbar u-sidebar__body">
                                     <div id="headerSidebarContent"
                                         class="u-sidebar__content u-header-sidebar__content">
                                         <!-- Logo -->
                                         <a class="order-1 order-xl-0 navbar-brand u-header__navbar-brand u-header__navbar-brand-center"
                                             href="{{ route('home.index') }}" aria-label="Computer Gear">
                                             <svg version="1.1" width="350px" height="60px" viewBox="0 0 350 60"
                                                 xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 0;">

                                                 <text x="10" y="40" font-size="38" fill="#333E48" font-weight="bold"
                                                     font-family="Arial, sans-serif">COMPUTER GEAR</text>
                                             </svg>
                                         </a>
                                         <!-- End Logo -->

                                         <!-- List -->
                                         <ul id="headerSidebarList" class="u-header-collapse__nav list-unstyled mb-0">
                                             <!-- Item -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center"
                                                     href="{{ route('home.index') }}">
                                                     <i class="fas fa-home mr-2"></i> Trang chủ
                                                 </a>
                                             </li>


                                             <!-- SẢN PHẨM -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center collapsed"
                                                     href="#collapseProducts" data-toggle="collapse" role="button"
                                                     aria-expanded="false">
                                                     <i class="fas fa-laptop mr-2"></i> Sản phẩm
                                                     <i class="ml-auto fas fa-chevron-down"></i>
                                                 </a>
                                                 <div class="collapse" id="collapseProducts">
                                                     <ul class="ml-4 list-unstyled">
                                                         <li>
                                                             <a class="u-header-collapse__nav-link font-weight-bold "
                                                                 href="{{ route('client.products.index') }}">
                                                                 Tất cả sản phẩm
                                                             </a>
                                                         </li>
                                                         @foreach ($categories as $category)
                                                             <li>
                                                                 <a class="u-header-collapse__nav-link"
                                                                     href="{{ route('client.products.category', $category->slug) }}">
                                                                     {{ $category->name }}
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             </li>

                                             <!-- BÀI VIẾT -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center collapsed"
                                                     href="#collapsePosts" data-toggle="collapse" role="button"
                                                     aria-expanded="false">
                                                     <i class="fas fa-newspaper mr-2"></i> Bài viết
                                                     <i class="ml-auto fas fa-chevron-down"></i>
                                                 </a>
                                                 <div class="collapse" id="collapsePosts">
                                                     <ul class="ml-4 list-unstyled">
                                                         <li>
                                                             <a class="u-header-collapse__nav-link font-weight-bold"
                                                                 href="{{ route('blog.index') }}">
                                                                 Tất cả bài viết
                                                             </a>
                                                         </li>
                                                         @foreach ($categories_post as $categoryPost)
                                                             <li>
                                                                 <a class="u-header-collapse__nav-link"
                                                                     href="{{ route('blog.index', $categoryPost->slug) }}">
                                                                     {{ $categoryPost->name }}
                                                                 </a>
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 </div>
                                             </li>





                                             <!-- Item -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center"
                                                     href="{{ route('about-us') }}">
                                                     <i class="fas fa-users mr-2"></i> Về chúng tôi
                                                 </a>
                                             </li>

                                             <!-- Item -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center"
                                                     href="{{ route('faqs') }}">
                                                     <i class="fas fa-question-circle mr-2"></i> Câu hỏi thường gặp
                                                 </a>
                                             </li>

                                             <!-- Item -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center"
                                                     href="{{ route('client.contacts.index') }}">
                                                     <i class="fas fa-envelope mr-2"></i> Liên hệ
                                                 </a>
                                             </li>

                                             <!-- Item -->
                                             <li class="u-has-submenu">
                                                 <a class="u-header-collapse__nav-link d-flex align-items-center"
                                                     href="{{ route('wishlist.index') }}">
                                                     <i class="fas fa-heart mr-2"></i> Danh sách yêu thích
                                                 </a>
                                             </li>
                                         </ul>

                                         <!-- End List -->
                                     </div>
                                 </div>
                                 <!-- End Content -->
                             </div>
                         </div>
                     </div>
                 </aside>
                 <!-- ========== END HEADER SIDEBAR ========== -->
             </div>
             <!-- End Logo-offcanvas-menu -->
             <!-- Primary Menu -->
             <div class="col d-none d-xl-block">
                 <!-- Nav -->
                 <nav class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space">
                     <!-- Navigation -->
                     <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                         <ul class="navbar-nav u-header__navbar-nav">
                             <!-- Home -->
                             <li class="nav-item hs-has-sub-menu u-header__nav-item">
                                 <a class="nav-link u-header__nav-link" href="{{ route('home.index') }}">Trang
                                     chủ</a>
                             </li>
                             <!-- End Home -->

                             <!-- Pages -->
                             <li class="nav-item hs-has-mega-menu u-header__nav-item">


                                 <a class="nav-link u-header__nav-link"
                                     href="{{ route('client.products.index') }}">Sản

                                     phẩm</a>
                             </li>
                             <!-- End Pages -->

                             <!-- Blog -->
                             <li class="nav-item hs-has-sub-menu u-header__nav-item">
                                 <a class="nav-link u-header__nav-link " href="{{ route('blog.index') }}"
                                     aria-haspopup="true" aria-expanded="false">Bài viết</a>
                             </li>
                             <!-- End Blog -->
                             <li class="nav-item u-header__nav-item">
                                 <a class="nav-link u-header__nav-link" href="{{ route('about-us') }}">Về chúng
                                     tôi</a>
                             </li>

                             <li class="nav-item u-header__nav-item">
                                 <a class="nav-link u-header__nav-link" href="{{ route('faqs') }}">Câu hỏi thường
                                     gặp</a>
                             </li>

                             <li class="nav-item u-header__nav-item">
                                 <a class="nav-link u-header__nav-link"
                                     href="{{ route('client.contacts.index') }}">Liên hệ</a>
                             </li>


                         </ul>
                     </div>
                     <!-- End Navigation -->
                 </nav>
                 <!-- End Nav -->
             </div>
             <!-- End Primary Menu -->
             <!-- Customer Care -->
             <div class="d-none d-xl-block col-md-auto">
                 <div class="d-flex">
                     <i class="ec ec-support font-size-50 text-primary"></i>
                     <div class="ml-2">
                         <div class="phone">
                             <strong>Hỗ trợ</strong> <a href="tel:800856800604" class="text-gray-90">(+800) 856 800
                                 604</a>
                         </div>
                         <div class="email">
                             E-mail: <a href="mailto:info@electro.com?subject=Help Need"
                                 class="text-gray-90">DoDuy123@gmail.com</a>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- End Customer Care -->
             <!-- Header Icons -->
             <div class="d-xl-none col col-xl-auto text-right text-xl-left pl-0 pl-xl-3 position-static">
                 <div class="d-inline-flex">
                     <ul class="d-flex list-unstyled mb-0 align-items-center">
                         <!-- Search -->
                         <li class="col d-xl-none px-2 px-sm-3 position-static">
                             <a id="searchClassicInvoker"
                                 class="font-size-22 text-gray-90 text-lh-1 btn-text-secondary" href="javascript:;"
                                 role="button" data-toggle="tooltip" data-placement="top" title="Search"
                                 aria-controls="searchClassic" aria-haspopup="true" aria-expanded="false"
                                 data-unfold-target="#searchClassic" data-unfold-type="css-animation"
                                 data-unfold-duration="300" data-unfold-delay="300" data-unfold-hide-on-scroll="true"
                                 data-unfold-animation-in="slideInUp" data-unfold-animation-out="fadeOut">
                                 <span class="ec ec-search"></span>
                             </a>

                             <!-- Input -->
                             <div id="searchClassic"
                                 class="dropdown-menu dropdown-unfold dropdown-menu-right left-0 mx-2"
                                 aria-labelledby="searchClassicInvoker">
                                 <form class="js-focus-state input-group px-3">
                                     <input class="form-control" type="search" placeholder="Search Product">
                                     <div class="input-group-append">
                                         <button class="btn btn-primary px-3" type="button"><i
                                                 class="font-size-18 ec ec-search"></i></button>
                                     </div>
                                 </form>
                             </div>
                             <!-- End Input -->
                         </li>
                         <!-- End Search -->
                         <li class="col d-none d-xl-block"><a
                                 href="https://transvelo.github.io/electro-html/2.0/html/shop/compare.html"
                                 class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Compare"><i
                                     class="font-size-22 ec ec-compare"></i></a></li>
                         <li class="col d-none d-xl-block"><a
                                 href="https://transvelo.github.io/electro-html/2.0/html/shop/wishlist.html"
                                 class="text-gray-90" data-toggle="tooltip" data-placement="top"
                                 title="Favorites"><i class="font-size-22 ec ec-favorites"></i></a></li>
                         <li class="col d-xl-none px-2 px-sm-3"><a
                                 href="https://transvelo.github.io/electro-html/2.0/html/shop/my-account.html"
                                 class="text-gray-90" data-toggle="tooltip" data-placement="top"
                                 title="My Account"><i class="font-size-22 ec ec-user"></i></a></li>
                         <li class="col pr-xl-0 px-2 px-sm-3">
                             <a href="https://transvelo.github.io/electro-html/2.0/html/shop/cart.html"
                                 class="text-gray-90 position-relative d-flex " data-toggle="tooltip"
                                 data-placement="top" title="Giỏ hàng">
                                 <i class="font-size-22 ec ec-shopping-bag"></i>
                                 <span
                                     class="width-22 height-22 bg-dark position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12 text-white">2</span>
                                 <span
                                     class="d-none d-xl-block font-weight-bold font-size-16 text-gray-90 ml-3">$1785.00</span>
                             </a>
                         </li>
                     </ul>
                 </div>
             </div>
             <!-- End Header Icons -->
         </div>
     </div>
 </div>
 <!-- End Logo and Menu -->
