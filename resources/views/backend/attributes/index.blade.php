<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý thuộc tính</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Quản lý thuộc tính</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>
                        <a href="{{ route($urlBase . 'create') }}" class="btn btn-primary">Thêm
                            mới</a>

                        <!-- Table with stripped rows -->
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-top">
                                <div class="datatable-dropdown">
                                    <label>
                                        <select class="datatable-selector" name="per-page">
                                            <option value="5">5</option>
                                            <option value="10" selected="">10</option>
                                            <option value="15">15</option>
                                            <option value="-1">All</option>
                                        </select> entries per page
                                    </label>
                                </div>
                                <div class="datatable-search">
                                    <input class="datatable-input" placeholder="Search..." type="search" name="search"
                                        title="Search within table">
                                </div>
                            </div>
                            <div class="datatable-container">
                                <table class="table datatable datatable-table table-bordered">
                                    <thead>
                                        <tr>

                                            <th class="text-center">ID</th>
                                            <th class="text-center">Tên thuộc tính</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="id"><span
                                                            class="maintitle">{{ $item->id }}</span></div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="name"><span
                                                            class="maintitle">{{ $item->name }}</span></div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $item->status ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                                                    </span>
                                                </td>


                                                <td class="text-center text-nowrap" style="width: 1px;">
                                                    <a href="{{ route($urlBase . 'show', $item) }}"
                                                        class="btn btn-success">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route($urlBase . 'edit', $item) }}"
                                                        class="btn btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route($urlBase . 'destroy', $item) }}"
                                                        method="post" id="item-{{ $item->id }}"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc muốn xoá?');">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Table with stripped rows -->
                        <div class="d-flex justify-content-center mt-2">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
