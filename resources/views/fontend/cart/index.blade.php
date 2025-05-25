        <main id="content" role="main" class="cart-page">
            <div class="container bg-md-transparent" style="padding: 10px; border-bottom: 1px solid #9d9c9c;">
                <div class="container">
                    <div class="my-md-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a
                                        href="{{ route('home.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">
                                    Giỏ hàng</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <style>
                .table {
                    background-color: #F0F7FF;
                    /* Light Blue Background */
                    border: 1.2px solid #2C5282;
                    /* Deep Blue Border */
                    border-radius: 6px;
                }

                .table th {
                    background-color: #2B6CB0;
                    /* Rich Blue */
                    color: #FFFFFF;
                    /* White Text */
                    border-bottom: 1.2px solid #2C5282;
                    padding: 12px;
                }

                .table td {
                    border-top: 1px solid #4299E1;
                    /* Medium Blue */
                    color: #2D3748;
                    /* Dark Gray Text */
                    padding: 10px;
                }

                /* Add fixed width for total price column */
                .table th.product-subtotal,
                .table td[data-title="Total"] {
                    width: 150px;
                    min-width: 150px;
                    max-width: 150px;
                    text-align: right;
                    white-space: nowrap;
                }

                .btn-primary-dark-w {
                    background-color: #3182CE;
                    /* Bright Blue */
                    color: #FFFFFF;
                    border: none;
                    border-radius: 5px;
                    transition: background-color 0.3s ease;
                }

                .btn-primary-dark-w:hover {
                    background-color: #2C5282;
                    /* Darker Blue on Hover */
                }

                /* Custom checkbox styles */
                input[type="checkbox"] {
                    appearance: none;
                    -webkit-appearance: none;
                    width: 20px;
                    height: 20px;
                    border: 2px solid #4299E1;
                    /* Medium Blue */
                    border-radius: 4px;
                    background-color: #fff;
                    cursor: pointer;
                    position: relative;
                    transition: all 0.2s ease;
                }

                input[type="checkbox"]:checked {
                    background-color: #fff;
                    border-color: #3182CE;
                    /* Bright Blue */
                }

                input[type="checkbox"]:checked::after {
                    content: '✓';
                    position: absolute;
                    color: #3182CE;
                    /* Bright Blue */
                    font-size: 14px;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }

                input[type="checkbox"]:hover {
                    border-color: #3182CE;
                    /* Bright Blue */
                    box-shadow: 0 0 3px rgba(49, 130, 206, 0.2);
                    /* Blue Shadow */
                }

                input[type="checkbox"]:focus {
                    outline: none;
                    box-shadow: 0 0 3px rgba(49, 130, 206, 0.3);
                    /* Blue Focus Shadow */
                }

                /* Additional tech-style elements */
                .cart-page {
                    background-color: #F7FAFC;
                    /* Very Light Blue Background */
                }

                .btn-soft-secondary {
                    background-color: #EBF8FF;
                    /* Very Light Blue */
                    color: #2B6CB0;
                    /* Rich Blue */
                    border: 1px solid #BEE3F8;
                    /* Light Blue Border */
                    transition: all 0.3s ease;
                }

                .btn-soft-secondary:hover {
                    background-color: #BEE3F8;
                    /* Light Blue */
                    color: #2C5282;
                    /* Deep Blue */
                }

                .alert {
                    border-left: 4px solid #3182CE;
                    /* Blue Alert Border */
                }

                .alert-warning {
                    background-color: #EBF8FF;
                    /* Light Blue Background */
                    border-color: #BEE3F8;
                    /* Light Blue Border */
                    color: #2B6CB0;
                    /* Rich Blue Text */
                }

                .alert-link {
                    color: #2C5282;
                    /* Deep Blue Links */
                    text-decoration: underline;
                }

                .alert-link:hover {
                    color: #1A365D;
                    /* Darker Blue on Hover */
                }
            </style>
            <div class="container">
                <div class="mb-4">
                    <h1 class="text-center">Giỏ hàng</h1>
                </div>
                <div class="mb-10 cart-table">
                    <form action="{{ route('cart.bulkDelete') }}" id="cart-form" method="POST">
                        @csrf
                        <div class="d-flex justify-content-end mb-4">
                            <button type="submit" id="delete-selected"
                                class="btn btn-block btn-dark ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block mx-2 mb-2"
                                style="border-radius: 0%">
                                Xoá những mục đã chọn
                            </button>
                        </div>
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="select-all"></th>
                                    <!-- Select All Checkbox -->
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Tên sản phẩm</th>
                                    <th class="product-price">Giá tiền</th>
                                    <th class="product-quantity w-lg-15">Số lượng</th>
                                    <th class="product-subtotal">Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasInvalidItems = false;
                                @endphp
                                @foreach ($cartItems as $item)
                                    @if (!$item->productVariant && $item->product->is_variant)
                                        @php
                                            $hasInvalidItems = true;
                                            continue;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                                class="select-item">
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cart.remove', $item->id) }}"
                                                class="text-gray-32 font-size-26">×</a>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <a href="#">
                                                <img class="img-fluid max-width-150 p-1 border border-color-1"
                                                    src="{{ asset('storage/' . $item->product->thumbnail ?? 'default-image.jpg') }}"
                                                    alt="{{ $item->product->name }}">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-90">{{ $item->product->name }}</a>
                                            @if ($item->productVariant)
                                                <div class="variant-attributes">
                                                    @php
                                                        $groupedAttributes = [];
                                                        foreach ($item->productVariant->attributeValues as $value) {
                                                            if (isset($value->attribute)) {
                                                                $attrName = $value->attribute->name;
                                                                if (!isset($groupedAttributes[$attrName])) {
                                                                    $groupedAttributes[$attrName] = $value->value;
                                                                }
                                                            }
                                                        }
                                                        ksort($groupedAttributes);
                                                        $formattedAttributes = [];
                                                        foreach ($groupedAttributes as $name => $value) {
                                                            $formattedAttributes[] = $name . ': ' . $value;
                                                        }
                                                    @endphp
                                                    <small class="text-dark d-block">
                                                        {{ implode(' | ', $formattedAttributes) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td data-title="Price">
                                            @php
                                                $price = $item->productVariant
                                                    ? $item->productVariant->price_sale ?? $item->productVariant->price
                                                    : $item->product->price_sale ?? $item->product->price;
                                            @endphp
                                            <span>{{ number_format($price, 0, ',', '.') }}₫</span>
                                        </td>

                                        <td data-title="Quantity">
                                            <div class="border rounded-pill py-1 width-122 w-xl-80 px-3 border-color-1"
                                                style="background: white;">
                                                <div class="js-quantity row align-items-center">
                                                    <div class="col">
                                                        <input type="hidden" name="cart[{{ $item->id }}][id]"
                                                            value="{{ $item->id }}">
                                                        <input
                                                            class="js-result form-control h-auto border-0 rounded p-0 shadow-none"
                                                            type="number" name="cart[{{ $item->id }}][quantity]"
                                                            value="{{ $item->quantity }}" min="1"
                                                            max="{{ $item->productVariant ? $item->productVariant->quantity : $item->product->quantity }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td data-title="Total">
                                            <span
                                                class="">{{ number_format($item->quantity * $price, 0, ',', '.') }}₫</span>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($hasInvalidItems)
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Một số sản phẩm trong giỏ hàng không còn khả dụng.
                                                <a href="{{ route('cart.clear') }}" class="alert-link">Xóa giỏ hàng</a>
                                                hoặc <a href="{{ route('cart.index') }}" class="alert-link">làm mới
                                                    trang</a>
                                                để cập nhật.
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td colspan="8" class="border-top space-top-2 justify-content-center">
                                        <div class="pt-md-3">
                                            <div class="d-block d-md-flex flex-center-between">
                                                <div class="mb-3 mb-md-0 w-xl-40"></div>

                                                <!-- Nút cập nhật giỏ hàng -->
                                                <div class="d-md-flex">
                                                    {{-- <button type="submit" id="update-cart"
                                                        class="btn btn-soft-secondary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 w-100 w-md-auto">
                                                        Cập nhật giỏ hàng
                                                    </button> --}}

                                                    <!-- Nút thanh toán -->
                                                    <button type="submit" id="checkout-selected"
                                                        class="btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block">
                                                        Tiến hành thanh toán
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </form>
                </div>

            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const userId = {{ auth()->id() }};

                    document.getElementById('select-all').addEventListener('click', function(event) {
                        document.querySelectorAll('.select-item').forEach(checkbox => {
                            checkbox.checked = event.target.checked;
                        });
                    });

                    document.getElementById('checkout-selected').addEventListener('click', function(event) {
                        event.preventDefault();

                        let selectedItems = document.querySelectorAll('.select-item:checked');
                        if (selectedItems.length === 0) {
                            alert('Vui lòng chọn ít nhất một mục để thanh toán ');
                            return;
                        }

                        // Get selected item IDs
                        let selectedIds = Array.from(selectedItems).map(item => item.value);

                        // Log selected items for debugging
                        console.log('Selected items for checkout:', selectedIds);

                        // Create URL with selected items
                        let checkoutUrl = "{{ route('checkout.index') }}?selected_items=" + selectedIds.join(',');

                        // Redirect to checkout page with selected items
                        window.location.href = checkoutUrl;
                    });

                    document.getElementById('delete-selected').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default form submission
                        let selectedItems = document.querySelectorAll('.select-item:checked');
                        if (selectedItems.length === 0) {
                            alert('Vui lòng chọn ít nhất một mục để xóa.');
                            return;
                        }
                        if (confirm('Are you sure you want to delete the selected items?')) {
                            let form = document.getElementById('cart-form');
                            if (form) {
                                form.action = "{{ route('cart.bulkDelete') }}";
                                form.submit();
                            } else {
                                console.error("Form not found!");
                            }

                        }
                    });

                    // Add quantity change handlers
                    document.querySelectorAll('.js-result').forEach(input => {
                        input.addEventListener('change', function() {
                            const itemId = this.name.match(/\[(\d+)\]/)[1];
                            const newQuantity = parseInt(this.value);
                            const maxQuantity = parseInt(this.getAttribute('max'));
                            const originalValue = this.defaultValue;

                            if (newQuantity > maxQuantity) {
                                alert('Số lượng sản phẩm vượt quá tồn kho.');
                                this.value = maxQuantity;
                                return;
                            }

                            // Get CSRF token from meta tag
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content');

                            // Update quantity via AJAX
                            fetch('{{ route('cart.update') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        cart: [{
                                            id: itemId,
                                            quantity: newQuantity
                                        }]
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Update the total price for this item
                                        const row = this.closest('tr');
                                        const priceCell = row.querySelector(
                                            'td[data-title="Price"] span');
                                        const totalCell = row.querySelector(
                                            'td[data-title="Total"] span');

                                        if (priceCell && totalCell) {
                                            // Get the price value (remove currency symbol and dots)
                                            const priceText = priceCell.textContent.replace(/[^\d]/g,
                                                '');
                                            const price = parseInt(priceText);
                                            const newTotal = price * newQuantity;

                                            // Update the total price with proper formatting
                                            totalCell.textContent = newTotal.toLocaleString('vi-VN') +
                                                '₫';

                                            // Store the new value as the default for future changes
                                            this.defaultValue = newQuantity;
                                        }

                                        // Show success message
                                        const toast = Swal.mixin({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true
                                        });

                                        toast.fire({
                                            icon: 'success',
                                            title: data.message ||
                                                'Cập nhật giỏ hàng thành công!'
                                        });
                                    } else {
                                        throw new Error(data.message ||
                                            'Có lỗi xảy ra khi cập nhật số lượng.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Reset to previous value
                                    this.value = originalValue;

                                    // Show error message
                                    const toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });

                                    toast.fire({
                                        icon: 'error',
                                        title: error.message ||
                                            'Có lỗi xảy ra khi cập nhật số lượng. Vui lòng thử lại.'
                                    });
                                });
                        });

                        // Add input event for immediate feedback
                        input.addEventListener('input', function() {
                            const newQuantity = parseInt(this.value) || 0;
                            const maxQuantity = parseInt(this.getAttribute('max'));

                            if (newQuantity > maxQuantity) {
                                this.value = maxQuantity;
                                return;
                            }

                            const row = this.closest('tr');
                            const priceCell = row.querySelector('td[data-title="Price"] span');
                            const totalCell = row.querySelector('td[data-title="Total"] span');

                            if (priceCell && totalCell) {
                                const priceText = priceCell.textContent.replace(/[^\d]/g, '');
                                const price = parseInt(priceText);
                                const newTotal = price * newQuantity;
                                totalCell.textContent = newTotal.toLocaleString('vi-VN') + '₫';
                            }
                        });
                    });

                    // Remove the update cart button click handler since we're updating in real-time
                    document.getElementById('update-cart').addEventListener('click', function(event) {
                        event.preventDefault();
                        // No need to do anything here anymore since updates happen in real-time
                    });

                    // Add WebSocket listener for cart updates
                    if (window.Echo) {
                        window.Echo.private(`cart.${userId}`)
                            .listen('CartUpdated', (e) => {
                                console.log('Cart updated:', e);

                                // Update cart badge count
                                const badge = document.getElementById('cart-badge-count');
                                if (badge) {
                                    badge.textContent = e.count;
                                }

                                // Update cart items
                                const cartTable = document.querySelector('.cart-table tbody');
                                if (cartTable) {
                                    // Make an AJAX request to get updated cart items
                                    fetch('{{ route('cart.index') }}')
                                        .then(response => response.text())
                                        .then(html => {
                                            const parser = new DOMParser();
                                            const doc = parser.parseFromString(html, 'text/html');
                                            const newCartTable = doc.querySelector('.cart-table tbody');
                                            if (newCartTable) {
                                                cartTable.innerHTML = newCartTable.innerHTML;
                                            }
                                        });
                                }
                            });
                    }

                    function formatPrice(price) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(price);
                    }
                });
            </script>

            @auth
                <script>
                    Echo.private('cart.{{ auth()->id() }}')
                        .listen('CartUpdated', (e) => {
                            document.getElementById("cartCount").innerText = e.cartCount;
                        });
                </script>
            @endauth
        </main>
