<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:51 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Initialize Laravel Echo
        window.Echo.channel('products')
            .listen('ProductUpdated', (e) => {
                console.log('Product updated:', e);
                // Update product details on the page
                const productElement = document.querySelector(`[data-product-id="${e.id}"]`);
                if (productElement) {
                    // Update product name
                    const nameElement = productElement.querySelector('.product-name');
                    if (nameElement) nameElement.textContent = e.name;
                    // Update product price
                    const priceElement = productElement.querySelector('.product-price');
                    if (priceElement) priceElement.textContent = e.price;
                    // Update product sale price if available
                    const salePriceElement = productElement.querySelector('.product-sale-price');
                    if (salePriceElement && e.price_sale) salePriceElement.textContent = e.price_sale;
                    // Update product quantity
                    const quantityElement = productElement.querySelector('.product-quantity');
                    if (quantityElement) quantityElement.textContent = e.quantity;
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
    </script>
</body>

<!-- Mirrored from transvelo.github.io/electro-html/2.0/html/home/home-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Feb 2025 19:17:55 GMT -->

</html>
