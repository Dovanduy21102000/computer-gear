console.log("realtime.js loaded");

import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Initialize Pusher and Echo
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
});

// Initialize Echo
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
});

// Add connection status logging
window.Echo.connector.pusher.connection.bind("connected", () => {
    console.log("✅ Connected to WebSocket server");

    // Subscribe to channels after connection is established
    window.Echo.channel("products")
        .listen("ProductUpdated", (e) => {
            console.log("Product updated:", e);
            // Find the product card by data-product-id
            const productElement = document.querySelector(
                `[data-product-id="${e.id}"]`
            );
            if (productElement) {
                // Update product name
                const nameElement =
                    productElement.querySelector(".product-name");
                if (nameElement) nameElement.textContent = e.name;

                // Format price
                const formatPrice = (price) =>
                    new Intl.NumberFormat("vi-VN").format(price) + "đ";

                // Update price and sale price
                const priceElement =
                    productElement.querySelector(".product-price");
                const salePriceElement = productElement.querySelector(
                    ".product-sale-price"
                );

                if (e.price_sale && salePriceElement) {
                    // If sale price exists, update both
                    salePriceElement.textContent = formatPrice(e.price_sale);
                    if (priceElement)
                        priceElement.textContent = formatPrice(e.price);
                } else if (priceElement) {
                    // If no sale price, just update price
                    priceElement.textContent = formatPrice(e.price);
                    if (salePriceElement) salePriceElement.textContent = "";
                }
            }
        })
        .listen("ProductCreated", (e) => {
            console.log("ProductCreated event received:", e);
            window.reloadProductGrid();
        })
        .listen("ProductDeleted", (e) => {
            console.log("ProductDeleted event received:", e);
            const el = document.querySelector(
                `[data-product-id="${e.productId}"]`
            );
            if (el) el.remove();
        })
        .listen("ProductStatusChanged", (e) => {
            console.log("ProductStatusChanged event received:", e);
            const el = document.querySelector(
                `[data-product-id="${e.product.id}"]`
            );
            if (el) {
                if (!e.product.status) {
                    el.classList.add("disabled");
                } else {
                    el.classList.remove("disabled");
                }
            }
        });

    window.Echo.channel("product-variants")
        .listen("ProductVariantCreated", (e) => {
            console.log("ProductVariantCreated event received:", e);
            if (typeof window.reloadHomeProductLists === "function") {
                window.reloadHomeProductLists();
            }
            if (typeof window.reloadProductGrid === "function") {
                window.reloadProductGrid();
            }
            if (typeof window.reloadProductDetailsSection === "function") {
                window.reloadProductDetailsSection();
            }
        })
        .listen("ProductVariantDeleted", (e) => {
            console.log("ProductVariantDeleted event received:", e);
            const el = document.querySelector(
                `[data-variant-id="${e.variantId}"]`
            );
            if (el) el.remove();
        })
        .listen("ProductVariantStatusChanged", (e) => {
            console.log("ProductVariantStatusChanged event received:", e);
            const el = document.querySelector(
                `[data-variant-id="${e.variant.id}"]`
            );
            if (el) {
                if (!e.variant.status) {
                    el.classList.add("disabled");
                } else {
                    el.classList.remove("disabled");
                }
            }
        });
});

window.Echo.connector.pusher.connection.bind("error", (error) => {
    console.error("❌ WebSocket connection error:", error);
});

// Helper function to render product cards
window.renderProductCard = function (product, view = "grid") {
    const formatPrice = (price) =>
        new Intl.NumberFormat("vi-VN").format(price) + "đ";
    let priceHtml = "";
    if (product.is_variant && product.variants && product.variants.length) {
        const prices = product.variants.map((v) => v.price);
        const salePrices = product.variants
            .map((v) => v.price_sale)
            .filter(Boolean);
        const minPrice = Math.min(...(salePrices.length ? salePrices : prices));
        const originalMin = Math.min(...prices);
        priceHtml = `<span class="text-danger fw-bold product-sale-price">${formatPrice(
            minPrice
        )}</span>`;
        if (salePrices.length) {
            priceHtml += `<br><del class="text-muted product-price">${formatPrice(
                originalMin
            )}</del>`;
        }
    } else if (product.price_sale) {
        priceHtml = `<del class="text-muted product-price">${formatPrice(
            product.price
        )}</del> <span class="text-danger product-sale-price">${formatPrice(
            product.price_sale
        )}</span>`;
    } else {
        priceHtml = `<span class="product-price">${formatPrice(
            product.price
        )}</span>`;
    }

    if (view === "list") {
        return `
<li class="product-item remove-divider" data-product-id="${product.id}">
    <div class="product-item__outer w-100">
        <div class="product-item__inner remove-prodcut-hover py-4 row">
            <div class="product-item__header col-6 col-md-4">
                <div class="mb-2">
                    <a href="/product/${
                        product.slug
                    }" class="d-block text-center">
                        <img class="img-fluid" src="/storage/${
                            product.thumbnail
                        }" alt="${product.name}">
                    </a>
                </div>
            </div>
            <div class="product-item__body col-6 col-md-5">
                <div class="pr-lg-10">
                    <div class="mb-2">
                        <a href="/products/category/${
                            product.category?.slug || "#"
                        }" class="font-size-12 text-gray-5">
                            ${product.category?.name || "Danh mục"}
                        </a>
                    </div>
                    <h5 class="mb-2 product-item__title">
                        <a href="/product/${
                            product.slug
                        }" class="text-blue font-weight-bold product-name">
                            ${product.name}
                        </a>
                    </h5>
                    <div class="prodcut-price mb-2">
                        ${priceHtml}
                    </div>
                    <ul class="font-size-12 p-0 text-gray-110 mb-4 d-none d-md-block">
                        <li class="line-clamp-1 mb-1 list-bullet">Chất lượng cao cấp</li>
                        <li class="line-clamp-1 mb-1 list-bullet">Thiết kế bền bỉ, chống sốc</li>
                        <li class="line-clamp-1 mb-1 list-bullet">Bảo hành chính hãng</li>
                    </ul>
                </div>
            </div>
            <div class="product-item__footer col-md-3 d-md-block">
                <div class="mb-3 d-flex flex-column align-items-center text-center">
                    <div class="prodcut-price mb-3 d-flex flex-column align-items-start">
                        ${priceHtml}
                    </div>
                    <div class="d-none d-xl-block prodcut-add-cart w-100">
                        <a href="/product/${
                            product.slug
                        }" class="btn btn-warning w-100 py-2 rounded-pill shadow-sm transition-3d-hover" style="font-size: 1rem; font-weight: 600; background: #ffc107; border: none;">
                            <i class="ec ec-add-to-cart mr-2"></i> Thêm vào giỏ hàng
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <a href="#" class="text-gray-6 font-size-13 mx-wd-3 d-flex align-items-center">
                        <i class="ec ec-favorites mr-1 font-size-15"></i> Yêu thích
                    </a>
                </div>
            </div>
        </div>
    </div>
</li>
        `;
    } else {
        // grid view
        return `
<li class="col-6 col-md-3 product-item" data-product-id="${product.id}">
    <div class="product-item__outer h-100">
        <div class="product-item__inner px-xl-4 p-3">
            <div class="product-item__body pb-xl-2">
                <div class="mb-2">
                    <a href="/products/category/${
                        product.category?.slug || "#"
                    }" class="font-size-12 text-gray-5">
                        ${product.category?.name || "Danh mục"}
                    </a>
                </div>
                <h5 class="mb-1 product-item__title">
                    <a href="/product/${
                        product.slug
                    }" class="text-blue font-weight-bold product-name">
                        ${product.name}
                    </a>
                </h5>
                <div class="mb-2">
                    <a href="/product/${
                        product.slug
                    }" class="d-block text-center">
                        <img class="img-fluid w-100" style="height: 150px; object-fit: cover;" src="/storage/${
                            product.thumbnail
                        }" alt="${product.name}">
                    </a>
                </div>
                <div class="flex-center-between mb-1">
                    <div class="prodcut-price">
                        ${priceHtml}
                    </div>
                    <div class="d-none d-xl-block prodcut-add-cart">
                        <a href="/product/${
                            product.slug
                        }" class="btn-add-cart btn-primary transition-3d-hover">
                            <i class="ec ec-add-to-cart"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="product-item__footer">
                <div class="border-top pt-2 flex-center-between flex-wrap">
                    <!-- Optionally add wishlist button here -->
                </div>
            </div>
        </div>
    </div>
</li>
        `;
    }
};
console.log(
    "window.renderProductCard is now defined:",
    typeof window.renderProductCard
);

window.reloadProductGrid = function () {
    const spinner = document.getElementById("productGridSpinner");
    if (spinner) spinner.style.display = "flex";
    fetch(window.location.pathname + window.location.search, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then((response) => response.text())
        .then((html) => {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;
            const newGrid = tempDiv.querySelector(".product-list");
            if (newGrid) {
                document.querySelector(".product-list").innerHTML =
                    newGrid.innerHTML;
            }
        })
        .finally(() => {
            if (spinner) spinner.style.display = "none";
        });
};

window.reloadHomeProductLists = function () {
    const selectors = [
        { list: ".home-new-products-list", spinner: "#homeProductListSpinner" },
        {
            list: ".home-featured-products-list",
            spinner: "#homeFeaturedProductListSpinner",
        },
        {
            list: ".home-top-selling-products-list",
            spinner: "#homeTopSellingProductListSpinner",
        },
        {
            list: ".home-discounted-products-list",
            spinner: "#homeDiscountedProductListSpinner",
        },
        {
            list: ".home-category-products-list",
            spinner: "#homeCategoryProductListSpinner",
        },
    ];
    selectors.forEach(({ list, spinner }) => {
        const spin = document.querySelector(spinner);
        if (spin) spin.style.display = "flex";
    });
    fetch("/", {
        // Always fetch the home page
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then((response) => response.text())
        .then((html) => {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;
            selectors.forEach(({ list, spinner }) => {
                const newList = tempDiv.querySelector(list);
                if (newList) {
                    document.querySelectorAll(list).forEach((el) => {
                        el.innerHTML = newList.innerHTML;
                    });
                }
            });
        })
        .finally(() => {
            selectors.forEach(({ spinner }) => {
                const spin = document.querySelector(spinner);
                if (spin) spin.style.display = "none";
            });
        });
};

// Add reloadProductDetailsSection function (to be used on product details page)
window.reloadProductDetailsSection = function () {
    const section = document.querySelector(".product-details-section");
    const spinner = document.getElementById("productDetailsSpinner");
    if (spinner) spinner.style.display = "flex";
    if (section) {
        fetch(window.location.pathname + window.location.search, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.text())
            .then((html) => {
                const tempDiv = document.createElement("div");
                tempDiv.innerHTML = html;
                const newSection = tempDiv.querySelector(
                    ".product-details-section"
                );
                if (newSection) {
                    section.innerHTML = newSection.innerHTML;
                }
            })
            .finally(() => {
                if (spinner) spinner.style.display = "none";
            });
    }
};
