<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="pagetitle">
        <h1>Danh sách thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <div class="col-sm-7">
                    <div>
                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                            data-bs-target="#showModal">
                            <a href="{{ route('attributes.create') }}" class="text-white">
                                <i class="ri-add-line align-bottom me-1"></i> Thêm thuộc tính
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
                                    <th>Tên thuộc tính</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attributes as $attribute)
                                    <tr>
                                        <td>{{ $attribute->id }}</td>
                                        <td>{{ $attribute->name }}</td>
                                        <td>
                                            @if($attribute->status)
                                                <span class="badge bg-success text-white rounded-pill py-2 px-4">Hiện</span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill py-2 px-4">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>{{ $attribute->created_at }}</td>
                                        <td>{{ $attribute->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('attributes.edit', $attribute->id) }}"
                                                class="btn btn-primary btn-sm">Sửa</a>

                                            {{-- <form action="{{ route('attributes.destroy', $attribute->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                            </form> --}}
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