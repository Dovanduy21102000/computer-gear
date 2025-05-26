<!DOCTYPE html>
<html lang="en">

@include('backend.dashboard.component.head')

<body>
    @include('backend.dashboard.component.nav')
    @include('backend.dashboard.component.sidebar')

    @include($template)

    @include('backend.dashboard.component.footer')
    @include('backend.dashboard.component.script')

    <!-- Custom JS -->
    <script src="{{ asset('js/slug-generator.js') }}"></script>

    @stack('scripts')

    @stack('variant_delete_forms')

</body>

</html>
