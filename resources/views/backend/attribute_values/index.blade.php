<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="pagetitle">
        <h1>Danh sách giá trị thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <div class="col-sm-7">
                    <div>
                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                            data-bs-target="#showModal">
                            <a href="{{ route('attribute-values.create') }}" class="text-white">
                                <i class="ri-add-line align-bottom me-1"></i> Thêm giá trị thuộc tính
                            </a>
                        </button>
                    </div>
                </div>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Table with stripped rows -->
                        <table class="table align-middle">
                            <thead class="table-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Thuộc tính</th>
                                    <th>Giá trị</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attributeValues as $attributeValue)
                                    <tr>
                                        <td>{{ $attributeValue->id }}</td>
                                        <td>{{ $attributeValue->attribute->name }}</td>
                                        <td>{{ $attributeValue->value }}</td>
                                        <td>{{ $attributeValue->created_at }}</td>
                                        <td>{{ $attributeValue->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('attribute-values.edit', $attributeValue->id) }}"
                                                class="btn btn-primary btn-sm">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->