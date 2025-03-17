<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:51 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
@include('fontend.component.head')

    <body>

        <!-- ========== HEADER ========== -->
        <header id="header" class="u-header u-header-left-aligned-nav">
            <div class="u-header__section">
                @include('fontend.component.topbar')

                <!-- Logo and Menu -->
                @include('fontend.component.menu')
                <!-- End Logo and Menu -->

                <!-- Vertical-and-Search-Bar -->
                @include('fontend.component.search')
                <!-- End Vertical-and-secondary-menu -->
            </div>
        </header>
        <!-- ========== END HEADER ========== -->

        <!-- ========== MAIN CONTENT ========== -->
        @include($template)
        <!-- ========== END MAIN CONTENT ========== -->

        <!-- ========== FOOTER ========== -->
        @include('fontend.component.footer')
        <!-- ========== END FOOTER ========== -->
       
       @include('fontend.component.script')
    </body>

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:55 GMT -->
</html>
