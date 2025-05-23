<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:51 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
@include('fontend.component.head')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

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
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    <script>
        // Initialize Pusher and Echo
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            wsHost: '{{ config('broadcasting.connections.pusher.options.host') }}',
            wsPort: {{ config('broadcasting.connections.pusher.options.port') }},
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
        });

        // Initialize Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            wsHost: '{{ config('broadcasting.connections.pusher.options.host') }}',
            wsPort: {{ config('broadcasting.connections.pusher.options.port') }},
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
        });

        // Add connection status logging
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('✅ Connected to WebSocket server');

            // Subscribe to channels after connection is established
            window.Echo.channel('products')
                .listen('ProductUpdated', (e) => {
                    console.log('Product updated:', e);
                    // Find the product card by data-product-id
                    const productElement = document.querySelector(`[data-product-id="${e.id}"]`);
                    if (productElement) {
                        // Update product name
                        const nameElement = productElement.querySelector('.product-name');
                        if (nameElement) nameElement.textContent = e.name;

                        // Format price
                        const formatPrice = (price) => new Intl.NumberFormat('vi-VN').format(price) + 'đ';

                        // Update price and sale price
                        const priceElement = productElement.querySelector('.product-price');
                        const salePriceElement = productElement.querySelector('.product-sale-price');

                        if (e.price_sale && salePriceElement) {
                            // If sale price exists, update both
                            salePriceElement.textContent = formatPrice(e.price_sale);
                            if (priceElement) priceElement.textContent = formatPrice(e.price);
                        } else if (priceElement) {
                            // If no sale price, just update price
                            priceElement.textContent = formatPrice(e.price);
                            if (salePriceElement) salePriceElement.textContent = '';
                        }
                    }
                });

            window.Echo.channel('variants')
                .listen('VariantUpdated', (e) => {
                    console.log('Variant updated:', e);
                    // Update variant details on the page
                    const variantElement = document.querySelector(`[data-variant-id="${e.id}"]`);
                    if (variantElement) {
                        // Update variant SKU
                        const skuElement = variantElement.querySelector('.variant-sku');
                        if (skuElement) skuElement.textContent = e.sku;
                        // Update variant price
                        const priceElement = variantElement.querySelector('.variant-price');
                        if (priceElement) priceElement.textContent = e.price;
                        // Update variant sale price if available
                        const salePriceElement = variantElement.querySelector('.variant-sale-price');
                        if (salePriceElement && e.price_sale) salePriceElement.textContent = e.price_sale;
                        // Update variant quantity
                        const quantityElement = variantElement.querySelector('.variant-quantity');
                        if (quantityElement) quantityElement.textContent = e.quantity;
                    }
                });
        });

        window.Echo.connector.pusher.connection.bind('error', (error) => {
            console.error('❌ WebSocket connection error:', error);
        });
    </script>
</body>

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:55 GMT -->

</html>
