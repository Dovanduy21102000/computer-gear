<main id="main" class="main">
    <div class="pagetitle">

        <h1>Thống Kê</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Thống kê</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="card-body">
                        <h5 class="card-title">Bộ lọc thống kê</h5>

                        <div class="row g-3">
                            <div class="col-md-auto">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dashboard.index', ['filterType' => 'today']) }}"
                                        class="btn {{ $filterType == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Hôm nay
                                    </a>
                                    <a href="{{ route('dashboard.index', ['filterType' => 'month']) }}"
                                        class="btn {{ $filterType == 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Tháng này
                                    </a>
                                    <a href="{{ route('dashboard.index', ['filterType' => 'year']) }}"
                                        class="btn {{ $filterType == 'year' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Năm nay
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-auto">
                                <form method="GET" action="{{ route('dashboard.index') }}" class="d-flex gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Từ</span>
                                        <input type="date" class="form-control" name="startDate"
                                            value="{{ request('startDate', $startDate ? $startDate->format('Y-m-d') : '') }}">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-text">Đến</span>
                                        <input type="date" class="form-control" name="endDate"
                                            value="{{ request('endDate', $endDate ? $endDate->format('Y-m-d') : '') }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Đơn hàng <span>| {{ $filterDisplay }}</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $orders ?? 0 }}</h6>
                                        <span
                                            class="{{ $growthPercentageOrders >= 0 ? 'text-success' : 'text-danger' }} small pt-1 fw-bold">
                                            {{ number_format((float) $growthPercentageOrders, 1) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ ((float) $growthPercentageOrders ?? 0) >= 0 ? 'Tăng' : 'Giảm' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Doanh thu <span>| {{ $filterDisplay }}</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($revenue1) }} đ</h6>
                                        <span
                                            class="{{ $percentageIncreaseRevenue >= 0 ? 'text-success' : 'text-danger' }} small pt-1 fw-bold">
                                            {{ number_format($percentageIncreaseRevenue, 1) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ $percentageIncreaseRevenue >= 0 ? 'Tăng' : 'Giảm' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Khách hàng <span>| {{ $filterDisplay }}</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $customers1 }}</h6>
                                        <span
                                            class="{{ $percentageChangeCustomers >= 0 ? 'text-success' : 'text-danger' }} small pt-1 fw-bold">
                                            {{ number_format($percentageChangeCustomers, 1) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ $percentageChangeCustomers >= 0 ? 'Tăng' : 'Giảm' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Customers Card -->
                    {{-- <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Lọc</h6>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.index', ['filter' => 'today']) }}">Hôm
                                            nay</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.index', ['filter' => 'month']) }}">Tháng
                                            này</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.index', ['filter' => 'year']) }}">Năm nay</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">


                                <h5 class="card-title">Đơn hàng <span>| {{ ucfirst($filter) }}</span></h5>


                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">

                                        <h6>{{ $orders ?? 0 }}</h6>
                                        <span class="text-success small pt-1 fw-bold">
                                            {{ number_format((float) $growthPercentageOrders) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ ((float) $growthPercentageOrders ?? 0) >= 0 ? 'Tăng' : 'Giảm bớt' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Hôm nay</a></li>
                                    <li><a class="dropdown-item" href="#">Tháng này</a></li>
                                    <li><a class="dropdown-item" href="#">Năm nay</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Doanh thu <span>| Tháng này</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($revenueThisMonth) }} đ</h6>
                                        <span class="text-success small pt-1 fw-bold">
                                            {{ number_format($percentageIncrease) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ $percentageIncrease >= 0 ? 'Tăng' : 'Giảm bớt' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Hôm nay</a></li>
                                    <li><a class="dropdown-item" href="#">Tháng này</a></li>
                                    <li><a class="dropdown-item" href="#">Năm nay</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Khách hàng <span>| Năm nay</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $customersThisYear }}</h6>
                                        <span
                                            class="{{ $percentageChange >= 0 ? 'text-success' : 'text-danger' }} small pt-1 fw-bold">
                                            {{ number_format($percentageChange) }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">
                                            {{ $percentageChange >= 0 ? 'Tăng' : 'Giảm bớt' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- End Customers Card --> --}}

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Hôm nay</a></li>
                                    <li><a class="dropdown-item" href="#">Tháng này</a></li>
                                    <li><a class="dropdown-item" href="#">Năm nay</a></li>
                                </ul>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Báo cáo <span>| Năm nay</span></h5>
                                    <div id="reportsChart"></div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            const chart = new ApexCharts(document.querySelector("#reportsChart"), {
                                                chart: {
                                                    type: 'area',
                                                    height: 350,
                                                    toolbar: {
                                                        show: false
                                                    }
                                                },
                                                series: [{
                                                        name: 'Bán hàng',
                                                        data: {!! json_encode($sales) !!}
                                                    },
                                                    {
                                                        name: 'Doanh thu (triệu)',
                                                        data: {!! json_encode($revenue) !!}
                                                    },
                                                    {
                                                        name: 'Khách hàng',
                                                        data: {!! json_encode($customers) !!}
                                                    }
                                                ],
                                                xaxis: {
                                                    categories: [
                                                        "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                                                        "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                                                    ]
                                                },
                                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: 2
                                                },
                                                markers: {
                                                    size: 4
                                                },
                                                fill: {
                                                    type: 'gradient',
                                                    gradient: {
                                                        shadeIntensity: 1,
                                                        opacityFrom: 0.3,
                                                        opacityTo: 0.4,
                                                        stops: [0, 90, 100]
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                tooltip: {
                                                    y: {
                                                        formatter: function(val, opts) {
                                                            // Hiển thị đơn vị "triệu" nếu là dòng doanh thu
                                                            const seriesName = opts.w.globals.seriesNames[opts.seriesIndex];
                                                            return seriesName.includes("Doanh thu") ? val + " triệu" : val;
                                                        }
                                                    }
                                                }
                                            });

                                            chart.render();
                                        });
                                    </script>
                                </div>

                            </div>

                        </div>
                    </div><!-- End Reports -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Hôm nay</a></li>
                                    <li><a class="dropdown-item" href="#">Tháng này</a></li>
                                    <li><a class="dropdown-item" href="#">Năm nay</a></li>
                                </ul>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Đơn hàng gần đây <span>| Hôm nay</span></h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Mã đơn hàng</th>
                                                <th scope="col">Khách hàng</th>
                                                <th scope="col">Tên sản phẩm</th>
                                                <th scope="col">Giá</th>
                                                <th scope="col">Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ordersLatest as $order)
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <th scope="row"><a
                                                                href="{{ route('orders.show', $order->id) }}">#{{ $order->code }}</a>
                                                        </th>
                                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                                        <td class="text-primary">
                                                            {{ $item->productVariant->sku ?? 'Sản phẩm không tồn tại' }}
                                                        </td>
                                                        <td>{{ number_format($order->final_price) }}</td>
                                                        <td>
                                                            @if ($order->status == 'approved')
                                                                <span class="badge bg-success">Đã duyệt</span>
                                                            @elseif ($order->status == 'pending')
                                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                            @elseif ($order->status == 'processing')
                                                                <span class="badge bg-info">Đang xử lý</span>
                                                            @elseif ($order->status == 'delivered')
                                                                <span class="badge bg-primary">Đang giao
                                                                    hàng</span>
                                                            @elseif ($order->status == 'completed')
                                                                <span class="badge bg-secondary">Đã giao
                                                                    hàng</span>
                                                            @elseif ($order->status == 'success')
                                                                <span class="badge bg-success">Hoàn thành</span>
                                                            @elseif ($order->status == 'canceled')
                                                                <span class="badge bg-dark">Đã hủy</span>
                                                            @else
                                                                <span class="badge bg-light">Không xác định</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                    <!-- Top Selling -->
                    <div class="col-12">
                        <div class="card top-selling overflow-auto">
                            <div class="card-body pb-0">
                                <h5 class="card-title">Sản phẩm bán chạy nhất <span>| Tháng này</span></h5>

                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Ảnh</th>
                                            <th scope="col">Sản phẩm</th>
                                            <th scope="col">Giá</th>
                                            <th scope="col">Đã bán</th>
                                            <th scope="col">Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topSellingProducts as $product)
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{ route('products.show', $product->id) }}"><img
                                                            src="{{ asset('storage/' . $product->thumbnail) }}"
                                                            alt="{{ $product->name }}" width="50"></a>
                                                </th>
                                                <td><a href="{{ route('products.show', $product->id) }}"
                                                        class="text-primary fw-bold">{{ $product->name }}</a></td>
                                                <td>{{ number_format($product->price) }}</td>
                                                <td class="fw-bold">{{ $product->quantity_sold }}</td>
                                                <td>{{ number_format($product->total_revenue) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Top Selling -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Hôm nay</a></li>
                            <li><a class="dropdown-item" href="#">Tháng này</a></li>
                            <li><a class="dropdown-item" href="#">Năm nay</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Hoạt động gần đây</h5>

                        <div class="activity">
                            @foreach ($recentActivities as $activity)
                                <div class="activity-item d-flex">
                                    {{-- <div class="activite-label">
                                        {{ $activity['created_at']->locale('vi')->diffForHumans() }}</div> --}}
                                    <i
                                        class='bi {{ $activity['type'] === 'order' ? 'bi-cart-fill text-success' : 'bi-person-fill text-primary' }} activity-badge align-self-start'></i>
                                    <div class="activity-content">
                                        @if ($activity['type'] === 'order')
                                            <a href="#" class="fw-bold text-dark">{{ $activity['name'] }}</a>
                                            vừa mua hàng
                                        @else
                                            Người dùng <a href="#"
                                                class="fw-bold text-dark">{{ $activity['name'] }}</a> vừa đăng ký
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div><!-- End Recent Activity -->
                <!-- News & Updates Traffic -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Tin tức &amp; Bài viết mới</h5>

                        <div class="news">
                            @foreach ($latestPosts as $post)
                                <div class="post-item clearfix">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                                    <h4><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                                    </h4>
                                    <p>{{ Str::limit($post->description, 100) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div><!-- End News & Updates -->

            </div><!-- End Right side columns -->

        </div>
    </section>

</main><!-- End #main -->
