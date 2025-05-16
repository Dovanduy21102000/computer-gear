<main id="main" class="main">
    <div class="pagetitle">
        <h1>Quản lý thương hiệu</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý thương hiệu</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>
                        <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary">
                     Thêm mới
                        </a>

                        <div class="table-responsive mt-3">
                            <table class="table datatable table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên thương hiệu</th>
                                        <th class="text-center">Logo</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                             <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">
                                                <img src="{{ $item->logo ? asset('storage/' . $item->logo) : asset('backend/img/mvc_logo.png') }}" 
                                                     alt="logo" width="80" class="img-thumbnail">
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->is_active ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                                                </span>
                                            </td>
                                            <td class="text-center" class="text-center text-nowrap">
                                                <a href="{{ route($urlBase . 'show', $item) }}" class="btn btn-success btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route($urlBase . 'edit', $item) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route($urlBase . 'destroy', $item) }}" method="post"
                                                      style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xoá?');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-2">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
