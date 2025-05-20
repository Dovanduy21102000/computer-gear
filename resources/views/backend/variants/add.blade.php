<main id="main" class="main">
    <div class="pagetitle">
        <h1>Thêm biến thể sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm biến thể</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ewqeqw</h5>
                        <form action="{{ route('variants.store', ['product' => $product->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Chọn thuộc tính section -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label">Chọn thuộc tính</label>
                                <div class="col-sm-10">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="row">
                                            @foreach ($attributes as $attribute)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input attribute-selector"
                                                            type="checkbox" id="attr_type_{{ $attribute->id }}"
                                                            value="{{ $attribute->id }}"
                                                            data-attribute-name="{{ $attribute->name }}">
                                                        <label class="form-check-label"
                                                            for="attr_type_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Chọn thuộc tính section -->

                            <div id="variants">
                                <div class="variant mb-4">
                                    <div class="row mb-3">
                                        <label for="sku" class="col-sm-2 col-form-label">SKU Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="sku" id="variant-sku"
                                                value="{{ old('sku', $suggestedSku ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="price" class="col-sm-2 col-form-label">Giá Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="price"
                                                value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="price_sale" class="col-sm-2 col-form-label">Giá khuyến mãi</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="price_sale"
                                                value="{{ old('price_sale') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="quantity" class="col-sm-2 col-form-label">Số lượng Biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="quantity"
                                                value="{{ old('quantity') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="image" class="col-sm-2 col-form-label">Ảnh biến
                                            thể</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" name="image" accept="image/*"
                                                onchange="previewVariantImage(event, 0)">
                                            <img id="variant-image-preview-0"
                                                style="max-width:80px; margin-top:5px; display:none;" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="variants[0][attributes]" class="col-sm-2 col-form-label">Thuộc
                                            tính</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                @foreach ($attributes as $attribute)
                                                    <div class="col-md-6 mb-3 attribute-group"
                                                        data-attribute-id="{{ $attribute->id }}"
                                                        style="display: none;">
                                                        <div class="p-2 border rounded bg-light">
                                                            <div class="fw-bold mb-2">{{ $attribute->name }}</div>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @foreach ($attribute->attributeValues as $value)
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="attribute_{{ $attribute->id }}_{{ $value->id }}_0"
                                                                            name="attributes[{{ $attribute->id }}]"
                                                                            value="{{ $value->id }}"
                                                                            {{ old('attributes.' . $attribute->id) == $value->id ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="attribute_{{ $attribute->id }}_{{ $value->id }}_0">
                                                                            {{ $value->value }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submitsubmit" class="btn btn-secondary">Thêm biến thể</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>

<script>
    let attributeGroupCount = 0;

    function addAttributeGroup() {
        const container = document.getElementById('dynamic-attributes');
        const groupIndex = attributeGroupCount++;
        const groupDiv = document.createElement('div');
        groupDiv.className = 'border rounded p-3 mb-3 bg-light';
        groupDiv.innerHTML = `
        <div class="mb-2 d-flex align-items-center">
            <input type="text" class="form-control me-2" name="new_attributes[${groupIndex}][name]" placeholder="Tên thuộc tính (VD: RAM, ROM, Màu...)" required style="max-width: 250px;">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="bi bi-trash"></i></button>
        </div>
        <div id="attribute-values-${groupIndex}" class="d-flex flex-wrap gap-2 mb-2"></div>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addAttributeValue(${groupIndex})">+ Thêm giá trị</button>
    `;
        container.appendChild(groupDiv);
    }

    function addAttributeValue(groupIndex) {
        const valuesDiv = document.getElementById(`attribute-values-${groupIndex}`);
        const valueIndex = valuesDiv.children.length;
        const valueDiv = document.createElement('div');
        valueDiv.className = 'input-group input-group-sm mb-1';
        valueDiv.style.maxWidth = '180px';
        valueDiv.innerHTML = `
        <input type="text" class="form-control" name="new_attributes[${groupIndex}][values][]" placeholder="Giá trị (VD: 8GB, Đỏ...)" required>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
    `;
        valuesDiv.appendChild(valueDiv);
    }

    function previewVariantImage(event, variantIndex) {
        const input = event.target;
        const preview = document.getElementById(`variant-image-preview-${variantIndex}`);
        if (input.files && input.files[0]) {
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Attribute type selection logic
    document.addEventListener('DOMContentLoaded', function() {
        const attributeSelectors = document.querySelectorAll('.attribute-selector');
        attributeSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const attributeId = this.value;
                const attributeGroups = document.querySelectorAll(
                    `.attribute-group[data-attribute-id="${attributeId}"]`);
                attributeGroups.forEach(group => {
                    group.style.display = this.checked ? 'block' : 'none';
                    const radioInputs = group.querySelectorAll('input[type="radio"]');
                    radioInputs.forEach(input => {
                        input.required = this.checked;
                    });
                });
            });
        });
    });
</script>

{{-- @extends('admin.layouts.master')

@section('title', 'Thống Kê')

@section('embed-css')
@endsection

@section('custom-css')
<style>
  .form-action select.form-control {
    position: static;
    width: 100%;
    font-size: 15px;
    line-height: 22px;
    padding: 5px;
    float: none;
    height: unset;
    border-color: #fbfbfb;
    box-shadow: none;
    background-color: #e8f0fe;
  }
</style>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Thống Kê</li>
</ol>
@endsection

@section('content')
  <!-- Main row -->
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <h3 class="box-title">Thống Kê Doanh Thu Bán Hàng</h3>

            <div class="form-action">
              <form action="{{ route('admin.statistic.edit') }}" method="POST" accept-charset="utf-8">
                @csrf
                <div class="row" style="margin-right: -5px; margin-left: -5px;">
                  <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 5px; padding-left: 5px;">
                    <select class="form-control change-statistic" name="month">
                      <option value="">-- Chọn Tháng --</option>
                      @for ($i = 0; $i < 12; $i++)
                        <option value="{{ $i + 1 }}">Tháng {{ $i + 1 }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 5px; padding-left: 5px;">
                    <select class="form-control change-statistic" name="year">
                      <option value="">-- Chọn Năm --</option>
                      @for ($i = 0; $i < 5; $i++)
                        <option value="{{ date('Y') - $i }}">Năm {{ date('Y') - $i }}</option>
                      @endfor
                    </select>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div id="print">
            <div class="box box-default box-chart">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Biểu Đồ Kinh Doanh Tháng {{ date('m').' Năm '.date('Y') }}</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" style="height: 300px;"></canvas>
                    </div>
                    <p class="text-center">
                      <i>Hình 1: Biểu đồ doanh số bán hàng</i>
                    </p>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="chart" style="margin-bottom: 10px;">
                      <!-- Sales Chart Canvas -->
                      <div id="quantityChart" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                    </div>
                    <!-- /.chart-responsive -->
                    <p class="text-center">
                      <i>Hình 2: Thị phần sản phẩm bán được theo nhà sản xuất</i>
                    </p>
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="chart" style="margin-bottom: 10px;">
                      <!-- Sales Chart Canvas -->
                      <div id="revenueChart" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                    </div>
                    <!-- /.chart-responsive -->
                    <p class="text-center">
                      <i>Hình 3: Thị phần doanh thu theo nhà sản xuất</i>
                    </p>
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="chart" style="margin-bottom: 10px;">
                      <!-- Sales Chart Canvas -->
                      <div id="profitChart" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                    </div>
                    <!-- /.chart-responsive -->
                    <p class="text-center">
                      <i>Hình 4: Thị phần lợi nhuận theo nhà sản xuất</i>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
              </div>
              <div class="box-footer" style="border-bottom: 1px solid #f4f4f4;">
                <div class="row">
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-3">
                    <div class="description-block border-right description-order">
                      <h5 class="description-header">{{ $data['count_orders'] }}</h5>
                      <span class="description-text">ĐƠN HÀNG</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <div class="col-sm-3 col-xs-3">
                    <div class="description-block border-right description-product">
                      <h5 class="description-header">{{ $data['count_products'] }}</h5>
                      <span class="description-text">SẢN PHẨM BÁN RA</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-3">
                    <div class="description-block border-right description-revenue">
                      <h5 class="description-header"><span style="color: #f30;">{{ number_format($data['total_revenue'],0,',','.').' VNĐ' }}</span></h5>
                      <span class="description-text">DOANH THU THÁNG</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-xs-3">
                    <div class="description-block description-profit">
                      <h5 class="description-header"><span style="color: #f30;">{{ number_format($data['total_profit'],0,',','.').' VNĐ' }}</span></h5>
                      <span class="description-text">LỢI NHUẬN THÁNG</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
              </div>
            </div>
            <div class="box box-default box-table">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Danh Sách Sản Phẩm Xuất Kho Tháng {{ date('m').' Năm '.date('Y') }}</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th style="text-align: center; vertical-align: middle;">STT</th>
                        <th style="vertical-align: middle;">Mã Sản Phẩm</th>
                        <th style="vertical-align: middle;">Tên Sản Phẩm</th>
                        <th style="vertical-align: middle;">Mầu Sắc</th>
                        <th style="vertical-align: middle;">Đơn Hàng</th>
                        <th style="vertical-align: middle;">Ngày Xuất</th>
                        <th style="text-align: center; vertical-align: middle;">Số Lượng</th>
                        <th style="vertical-align: middle;">Giá Nhập</th>
                        <th style="vertical-align: middle;">Giá Xuất</th>
                        <th style="vertical-align: middle;">Doanh Thu</th>
                        <th style="vertical-align: middle;">Lợi Nhuận</th>
                      </tr>
                    </thead>
                    <tbody>

                        <tr>
                          <td style="text-align: center; vertical-align: middle;">{{ $key + 1 }}</td>
                          <td style="vertical-align: middle;">{{ '#'.$order_detail->product_detail->product->sku_code }}</td>
                          <td style="vertical-align: middle;">{{ $order_detail->product_detail->product->name }}</td>
                          <td style="vertical-align: middle;">{{ $order_detail->product_detail->color }}</td>
                          <td style="vertical-align: middle;">{{ '#'.$order_detail->order->order_code }}</td>
                          <td style="vertical-align: middle;">{{ date_format($order_detail->created_at, 'd/m/Y') }}</td>
                          <td style="text-align: center; vertical-align: middle;">{{ $order_detail->quantity }}</td>
                          <td style="vertical-align: middle;"><span style="color: #f30;">{{ number_format($order_detail->product_detail->import_price,0,',','.') }} VNĐ</span></td>
                          <td style="vertical-align: middle;"><span style="color: #f30;">{{ number_format($order_detail->price,0,',','.') }} VNĐ</span></td>
                          <td style="vertical-align: middle;"><span style="color: #f30;">{{ number_format($order_detail->price * $order_detail->quantity,0,',','.') }} VNĐ</span></td>
                          <td style="vertical-align: middle;"><span style="color: #f30;">{{ number_format($order_detail->quantity * ($order_detail->price - $order_detail->product_detail->import_price),0,',','.') }} VNĐ</span></td>
                        </tr>
                      @endforeach
                      <tr>
                        <td colspan="11" style="text-align: right;">
                          <i style="margin-right: 10px;">*Tổng Doanh Thu = <span style="color: #f30;">{{ number_format($price,0,',','.') }} VNĐ</span></i>
                          <i>*Tổng Lợi Nhuận = <span style="color: #f30;">{{ number_format($profit,0,',','.') }} VNĐ</span></i>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ./box-body -->
        <div class="box-footer">
          <div class="row">
            <div class="col-xs-12">
              <button class="btn btn-success btn-print pull-right"><i class="fa fa-print"></i> In Báo Cáo</button>
            </div>
          </div>
        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
@endsection

@section('embed-js')
  <!-- ChartJS -->
  <script src="{{ asset('AdminLTE/bower_components/chart.js/Chart.js') }}"></script>
  <!-- FLOT CHARTS -->
  <script src="{{ asset('AdminLTE/bower_components/Flot/jquery.flot.js') }}"></script>
  <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
  <script src="{{ asset('AdminLTE/bower_components/Flot/jquery.flot.resize.js') }}"></script>
  <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
  <script src="{{ asset('AdminLTE/bower_components/Flot/jquery.flot.pie.js') }}"></script>
  <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
  <script src="{{ asset('AdminLTE/bower_components/Flot/jquery.flot.categories.js') }}"></script>
  <!-- Print JS -->
  <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
@endsection

@section('custom-js')
<script>
  $(document).ready(function(){
    // -----------------------
    // - MONTHLY SALES CHART -
    // -----------------------

    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart       = new Chart(salesChartCanvas);

    var salesChartData = {
      labels  : {!! json_encode($data['labels'] ) !!},
      datasets: [
        {
          label               : 'Doanh Số Bán Hàng',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : {!! json_encode($data['revenues'] ) !!}
        }
      ]
    };

    var salesChartOptions = {
      // Boolean - If we should show the scale at all
      showScale               : true,
      scaleLabel              : function(label) {
        return formatMoney(label.value);
      },
      tooltipTemplate        : function(label) {
        return label.label.toString() + " : " + formatMoney(label.value);
      },
      tooltipFontSize: 12,
      // Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : false,
      // String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      // Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      // Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      // Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      // Boolean - Whether the line is curved between points
      bezierCurve             : true,
      // Number - Tension of the bezier curve between points
      bezierCurveTension      : 0.3,
      // Boolean - Whether to show a dot for each point
      pointDot                : false,
      // Number - Radius of each point dot in pixels
      pointDotRadius          : 4,
      // Number - Pixel width of point dot stroke
      pointDotStrokeWidth     : 1,
      // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,
      // Boolean - Whether to show a stroke for datasets
      datasetStroke           : true,
      // Number - Pixel width of dataset stroke
      datasetStrokeWidth      : 2,
      // Boolean - Whether to fill the dataset with a color
      datasetFill             : true,
      // String - A legend template
      legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true,
      // Boolean - whether to make the chart responsive to window resizing
      responsive              : true
    };

    // Create the line chart
    var myChart = salesChart.Line(salesChartData, salesChartOptions);

    // ---------------------------
    // - END MONTHLY SALES CHART -
    // ---------------------------

    /* DONUT CHART */
    var options = {
      series: {
        pie: { show: true, radius: 1, innerRadius: 0.5,
          label: {
            show: true, radius: 2 / 3, formatter: labelFormatter, threshold: 0.1
          }
        }
      },
      colors: ['#3498db', '#2ecc71', '#e67e22', '#e74c3c', '#f1c40f', '#9b59b6', '#34495e'],
      legend: { show: false }
    };

    var quantityData = [
      @foreach ($data['producer'] as $key => $producer)
        { label: '{{ $key }}', data: {{ $producer['quantity'] }} },
      @endforeach
    ];

    var quantityChart = $.plot('#quantityChart', quantityData, options);

    var revenueData = [
      @foreach ($data['producer'] as $key => $producer)
        { label: '{{ $key }}', data: {{ $producer['quantity'] }} },
      @endforeach
    ];

    var revenueChart = $.plot('#revenueChart', revenueData, options);

    var profitData = [
      @foreach ($data['producer'] as $key => $producer)
        { label: '{{ $key }}', data: {{ $producer['profit'] }} },
      @endforeach
    ];

    var profitChart = $.plot('#profitChart', profitData, options);
    /* END DONUT CHART */

    $('select.change-statistic').on('change', function() {
      $(this).closest('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
      var url = $(this).closest('form').attr('action');
      var data = $(this).closest('form').serialize();
      $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(data) {
          console.log(data)
          $('div.overlay').remove();
          $('#print .box-chart .box-title').text(data.text.title1);
          $('#print .box-table .box-title').text(data.text.title2);
          $('#print .box-chart .description-order .description-header').text(data.count_orders);
          $('#print .box-chart .description-product .description-header').text(data.count_products);
          $('#print .box-chart .description-revenue .description-header span').text(formatMoney(data.total_revenue));
          $('#print .box-chart .description-revenue .description-text').text(data.text.revenue);
          $('#print .box-chart .description-profit .description-header span').text(formatMoney(data.total_profit));
          $('#print .box-chart .description-profit .description-text').text(data.text.profit);

          myChart.destroy();

          var salesChartData = {
            labels  : data.labels,
            datasets: [
              {
                label               : 'Doanh Số Bán Hàng',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : data.revenues
              }
            ]
          };

          myChart = salesChart.Line(salesChartData, salesChartOptions);

          quantityData = [];
          revenueData = [];
          profitData = [];

          $.each(data.producer, function(key,value){
            quantityData.push({ label: key, data: value.quantity });
            revenueData.push({ label: key, data: value.revenue });
            profitData.push({ label: key, data: value.profit });
          });

          quantityChart.destroy();
          revenueChart.destroy();
          profitChart.destroy();

          quantityChart = $.plot('#quantityChart', quantityData, options);
          revenueChart = $.plot('#revenueChart', revenueData, options);
          profitChart = $.plot('#profitChart', profitData, options);

          $('.box-table table tbody').empty();

          var price = 0;
          var profit = 0;

          $.each(data.order_details, function(key,value){

            price = price + value.price * value.quantity;
            profit = profit + value.quantity * (value.price - value.product_detail.import_price);

            $('.box-table table tbody').append(
              '<tr>' +
                '<td style="text-align: center; vertical-align: middle;">' + (key + 1) +'</td>' +
                '<td style="vertical-align: middle;"> #' + value.product_detail.product.sku_code + '</td>' +
                '<td style="vertical-align: middle;">' + value.product_detail.product.name + '</td>' +
                '<td style="vertical-align: middle;">' + value.product_detail.color + '</td>' +
                '<td style="vertical-align: middle;"> #' + value.order.order_code + '</td>' +
                '<td style="vertical-align: middle;">' + formatDate(value.created_at) + '</td>' +
                '<td style="text-align: center; vertical-align: middle;">' + value.quantity + '</td>' +
                '<td style="vertical-align: middle;">' +
                  '<span style="color: #f30;">' +
                    formatMoney(value.product_detail.import_price) +
                  '</span>' +
                '</td>' +
                '<td style="vertical-align: middle;">' +
                  '<span style="color: #f30;">' +
                    formatMoney(value.price) +
                  '</span>' +
                '</td>' +
                '<td style="vertical-align: middle;">' +
                  '<span style="color: #f30;">' +
                    formatMoney(value.price * value.quantity) +
                  '</span>' +
                '</td>' +
                '<td style="vertical-align: middle;">' +
                  '<span style="color: #f30;">' +
                    formatMoney(value.quantity * (value.price - value.product_detail.import_price)) +
                  '</span>' +
                '</td>' +
              '</tr>'
            );
          });

          $('.box-table table tbody').append(
            '<tr>' +
              '<td colspan="11" style="text-align: right;">' +
                '<i style="margin-right: 10px;">*Tổng Doanh Thu = <span style="color: #f30;">' + formatMoney(price) + '</span></i>' +
                '<i>*Tổng Lợi Nhuận = <span style="color: #f30;">' + formatMoney(profit) + '</span></i>' +
              '</td>' +
            '</tr>'
          );
        },
        error: function(data) {
          var errors = data.responseJSON;
          Swal.fire({
            title: 'Thất bại',
            text: errors.msg,
            type: 'error'
          })
        }
      });
    });
  });
</script>

<!-- Page script -->
<script>
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label + '<br>' + Math.round(series.percent) + '%</div>'
  }
  function formatMoney(argument) {
    return argument.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' VNĐ';
  }
  function formatDate(argument) {
    var date = new Date(argument);
    return date.getDate().toString() + '/' + (date.getMonth() + 1).toString() + '/' + date.getFullYear().toString();
  }
</script>
<script>
  $(document).ready(function() {
    $('.btn-print').click(function(){
      printJS({
        printable: 'print',
        type: 'html',
        documentTitle: ' ',
        header: 'Báo Cáo Tình Hình Kinh Doanh Website PhoneStore',
        headerStyle: 'font-size: 14px; margin-bottom: 10px;',
        style: '.box { margin-top: 10px; border-top: none; box-shadow: none; } ' +
                '@media print { .box-footer { page-break-after: always; } } ' +
                'table { page-break-inside:auto } ' +
                'tr { page-break-inside:avoid; page-break-after:auto }',
        css: [
          '{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}',
          '{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}'
        ]
      });
    });
  });
</script>
@endsection --}}