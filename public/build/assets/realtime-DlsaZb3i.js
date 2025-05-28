import{P as w,E as h}from"./pusher-BaBYCiB9.js";console.log("realtime.js loaded");new w("local",{cluster:"mt1",wsHost:"127.0.0.1",wsPort:"8000",forceTLS:!1,disableStats:!0,enabledTransports:["ws","wss"]});window.Echo=new h({broadcaster:"pusher",key:"local",cluster:"mt1",wsHost:"127.0.0.1",wsPort:"8000",forceTLS:!1,disableStats:!0,enabledTransports:["ws","wss"]});window.Echo.connector.pusher.connection.bind("connected",()=>{console.log("✅ Connected to WebSocket server"),window.Echo.channel("products").listen("ProductUpdated",e=>{console.log("Product updated:",e);const t=document.querySelector(".product-details-section");if(t){const n=t.querySelector(".product-name");n&&(n.textContent=e.name);const o=u=>new Intl.NumberFormat("vi-VN").format(u)+"đ",r=t.querySelector(".product-price"),c=t.querySelector(".product-sale-price");e.price_sale&&c?(c.textContent=o(e.price_sale),r&&(r.textContent=o(e.price))):r&&(r.textContent=o(e.price),c&&(c.textContent="")),t.querySelectorAll(".variant-price").forEach(u=>{const p=u.getAttribute("data-variant-id");if(p&&e.variants){const d=e.variants.find(f=>f.id==p);d&&(d.price_sale?u.innerHTML=`
                                    <span class="text-danger fw-bold">${o(d.price_sale)}</span>
                                    <del class="text-muted">${o(d.price)}</del>
                                `:u.textContent=o(d.price))}});const l=t.querySelector(".product-stock");l&&(l.textContent=e.quantity>0?`Còn ${e.quantity} sản phẩm`:"Hết hàng",l.className=`product-stock ${e.quantity>0?"text-success":"text-danger"}`);const s=t.querySelector(".btn-add-to-cart");s&&(e.quantity<=0?(s.disabled=!0,s.classList.add("disabled"),s.textContent="Hết hàng"):(s.disabled=!1,s.classList.remove("disabled"),s.textContent="Thêm vào giỏ hàng"))}const i=document.querySelector(`[data-product-id="${e.id}"]`);if(i){const n=i.querySelector(".product-name");n&&(n.textContent=e.name);const o=a=>new Intl.NumberFormat("vi-VN").format(a)+"đ",r=i.querySelector(".product-price"),c=i.querySelector(".product-sale-price");e.price_sale&&c?(c.textContent=o(e.price_sale),r&&(r.textContent=o(e.price))):r&&(r.textContent=o(e.price),c&&(c.textContent=""))}typeof window.reloadProductGrid=="function"&&window.reloadProductGrid()}).listen("ProductCreated",e=>{console.log("ProductCreated event received:",e),window.reloadProductGrid()}).listen("ProductDeleted",e=>{console.log("ProductDeleted event received:",e);const t=document.querySelector(`[data-product-id="${e.productId}"]`);t&&t.remove(),typeof window.reloadProductGrid=="function"&&window.reloadProductGrid(),typeof window.reloadHomeProductLists=="function"&&window.reloadHomeProductLists()}),window.Echo.channel("product-variants").listen("ProductVariantCreated",e=>{console.log("ProductVariantCreated event received:",e),typeof window.reloadHomeProductLists=="function"&&window.reloadHomeProductLists(),typeof window.reloadProductGrid=="function"&&window.reloadProductGrid(),typeof window.reloadProductAttributeOptions=="function"&&window.reloadProductAttributeOptions()}).listen("ProductVariantDeleted",e=>{console.log("ProductVariantDeleted event received:",e);const t=document.querySelector(`[data-variant-id="${e.variantId}"]`);t&&t.remove()}).listen("ProductVariantStatusChanged",e=>{console.log("ProductVariantStatusChanged event received:",e);const t=document.querySelector(`[data-variant-id="${e.variant.id}"]`);t&&(e.variant.status?t.classList.remove("disabled"):t.classList.add("disabled"))}),window.Echo.channel("variants").listen("VariantUpdated",e=>{console.log("VariantUpdated event received:",e),typeof window.reloadProductAttributeOptions=="function"&&window.reloadProductAttributeOptions()})});window.Echo.connector.pusher.connection.bind("error",e=>{console.error("❌ WebSocket connection error:",e)});window.renderProductCard=function(e,t="grid"){var o,r,c,a;const i=l=>new Intl.NumberFormat("vi-VN").format(l)+"đ";let n="";if(e.is_variant&&e.variants&&e.variants.length){const l=e.variants.map(d=>d.price),s=e.variants.map(d=>d.price_sale).filter(Boolean),u=Math.min(...s.length?s:l),p=Math.min(...l);n=`<span class="text-danger fw-bold product-sale-price">${i(u)}</span>`,s.length&&(n+=`<br><del class="text-muted product-price">${i(p)}</del>`)}else e.price_sale?n=`<del class="text-muted product-price">${i(e.price)}</del> <span class="text-danger product-sale-price">${i(e.price_sale)}</span>`:n=`<span class="product-price">${i(e.price)}</span>`;return t==="list"?`
<li class="product-item remove-divider" data-product-id="${e.id}">
    <div class="product-item__outer w-100">
        <div class="product-item__inner remove-prodcut-hover py-4 row">
            <div class="product-item__header col-6 col-md-4">
                <div class="mb-2">
                    <a href="/product/${e.slug}" class="d-block text-center">
                        <img class="img-fluid" src="/storage/${e.thumbnail}" alt="${e.name}">
                    </a>
                </div>
            </div>
            <div class="product-item__body col-6 col-md-5">
                <div class="pr-lg-10">
                    <div class="mb-2">
                        <a href="/products/category/${((o=e.category)==null?void 0:o.slug)||"#"}" class="font-size-12 text-gray-5">
                            ${((r=e.category)==null?void 0:r.name)||"Danh mục"}
                        </a>
                    </div>
                    <h5 class="mb-2 product-item__title">
                        <a href="/product/${e.slug}" class="text-blue font-weight-bold product-name">
                            ${e.name}
                        </a>
                    </h5>
                    <div class="prodcut-price mb-2">
                        ${n}
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
                        ${n}
                    </div>
                    <div class="d-none d-xl-block prodcut-add-cart w-100">
                        <a href="/product/${e.slug}" class="btn btn-warning w-100 py-2 rounded-pill shadow-sm transition-3d-hover" style="font-size: 1rem; font-weight: 600; background: #ffc107; border: none;">
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
        `:`
<li class="col-6 col-md-3 product-item" data-product-id="${e.id}">
    <div class="product-item__outer h-100">
        <div class="product-item__inner px-xl-4 p-3">
            <div class="product-item__body pb-xl-2">
                <div class="mb-2">
                    <a href="/products/category/${((c=e.category)==null?void 0:c.slug)||"#"}" class="font-size-12 text-gray-5">
                        ${((a=e.category)==null?void 0:a.name)||"Danh mục"}
                    </a>
                </div>
                <h5 class="mb-1 product-item__title">
                    <a href="/product/${e.slug}" class="text-blue font-weight-bold product-name">
                        ${e.name}
                    </a>
                </h5>
                <div class="mb-2">
                    <a href="/product/${e.slug}" class="d-block text-center">
                        <img class="img-fluid w-100" style="height: 150px; object-fit: cover;" src="/storage/${e.thumbnail}" alt="${e.name}">
                    </a>
                </div>
                <div class="flex-center-between mb-1">
                    <div class="prodcut-price">
                        ${n}
                    </div>
                    <div class="d-none d-xl-block prodcut-add-cart">
                        <a href="/product/${e.slug}" class="btn-add-cart btn-primary transition-3d-hover">
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
        `};console.log("window.renderProductCard is now defined:",typeof window.renderProductCard);window.reloadProductGrid=function(){const e=document.getElementById("productGridSpinner");e&&(e.style.display="flex"),fetch(window.location.pathname+window.location.search,{headers:{"X-Requested-With":"XMLHttpRequest"}}).then(t=>t.text()).then(t=>{const i=document.createElement("div");i.innerHTML=t;const n=i.querySelector(".product-list");n&&(document.querySelector(".product-list").innerHTML=n.innerHTML)}).finally(()=>{e&&(e.style.display="none")})};window.reloadHomeProductLists=function(){const e=[{list:".home-new-products-list",spinner:"#homeProductListSpinner"},{list:".home-featured-products-list",spinner:"#homeFeaturedProductListSpinner"},{list:".home-top-selling-products-list",spinner:"#homeTopSellingProductListSpinner"},{list:".home-discounted-products-list",spinner:"#homeDiscountedProductListSpinner"},{list:".home-category-products-list",spinner:"#homeCategoryProductListSpinner"}];e.forEach(({list:t,spinner:i})=>{const n=document.querySelector(i);n&&(n.style.display="flex")}),fetch("/",{headers:{"X-Requested-With":"XMLHttpRequest"}}).then(t=>t.text()).then(t=>{const i=document.createElement("div");i.innerHTML=t,e.forEach(({list:n,spinner:o})=>{const r=i.querySelector(n);r&&document.querySelectorAll(n).forEach(c=>{c.innerHTML=r.innerHTML})})}).finally(()=>{e.forEach(({spinner:t})=>{const i=document.querySelector(t);i&&(i.style.display="none")})})};window.reloadProductDetailsSection=function(){const e=document.getElementById("productDetailsSpinner");e&&(e.style.display="flex"),fetch(window.location.href,{headers:{"X-Requested-With":"XMLHttpRequest"}}).then(t=>t.text()).then(t=>{const i=document.createElement("div");i.innerHTML=t;const n=i.querySelector(".product-details-section");n&&(document.querySelector(".product-details-section").innerHTML=n.innerHTML)}).finally(()=>{e&&(e.style.display="none")})};window.reloadProductAttributeOptions=function(){const e=document.getElementById("attributeOptionsSpinner");e&&(e.style.display="block"),fetch(window.location.href,{headers:{"X-Requested-With":"XMLHttpRequest"}}).then(t=>t.text()).then(t=>{const i=document.createElement("div");i.innerHTML=t;const n=i.querySelector(".product-attribute-options");n&&(document.querySelector(".product-attribute-options").innerHTML=n.innerHTML),typeof fetchAllVariants=="function"?fetchAllVariants(function(){typeof window.initAttributeOptions=="function"&&window.initAttributeOptions()}):typeof window.initAttributeOptions=="function"&&window.initAttributeOptions()}).finally(()=>{const t=document.getElementById("attributeOptionsSpinner");t&&(t.style.display="none")})};window.fetchAllVariants=function(e){var i;const t=((i=document.querySelector('[name="product_id"]'))==null?void 0:i.value)||window.productId;t&&fetch(`/get-variant?product_id=${t}&get_all=true`,{headers:{"X-Requested-With":"XMLHttpRequest"}}).then(n=>n.json()).then(n=>{window.allVariants=n,console.log("All variants loaded:",window.allVariants),typeof e=="function"&&e()}).catch(n=>{console.error("Error loading variants:",n),typeof e=="function"&&e()})};typeof window.fetchAllVariants=="function"&&window.fetchAllVariants();var m;if(window.Echo){const e=(m=document.querySelector('meta[name="user-id"]'))==null?void 0:m.getAttribute("content");e&&window.Echo.private(`cart.${e}`).listen("CartUpdated",t=>{const i=document.getElementById("cart-badge-count");i&&(i.textContent=t.count);const n=document.querySelector(".cart-table tbody");n&&fetch("/cart").then(o=>o.text()).then(o=>{const a=new DOMParser().parseFromString(o,"text/html").querySelector(".cart-table tbody");a&&(n.innerHTML=a.innerHTML)})})}
