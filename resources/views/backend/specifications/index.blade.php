<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý Thông số Sản phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ $product->name }}</a></li>
                <li class="breadcrumb-item active">Thông số sản phẩm</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông số sản phẩm: {{ $product->name }}</h5>
                        <a href="{{ route('admin.specifications.create', ['product_id' => $product->id]) }}"
                            class="btn btn-primary mb-2">
                            <i class="bi bi-plus"></i> Thêm thông số
                        </a>

                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-container">
                                <table class="table datatable datatable-table table-bordered">
                                    <thead>
                                        <tr>

                                            <th class="text-center">Tên thông số</th>
                                            <th class="text-center">Giá trị</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($specifications as $index => $spec)
                                            <tr>

                                                <td>{{ $spec->key }}</td>
                                                <td>{{ $spec->value }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.specifications.edit', ['product_id' => $spec->product_id, 'id' => $spec->id]) }}" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i> Chỉnh sửa
                                                    </a>
                                                
                                                    

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
