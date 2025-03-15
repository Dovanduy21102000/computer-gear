<main id="main" class="main">

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>
                        <a href="{{ route($urlBase . 'create') }}" class="btn btn-danger"><i
                                class="fa fa-plus mr5"></i>Thêm
                            mới hãng</a>

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
                                <table class="table datatable datatable-table">
                                    <thead>
                                        <tr>
                                            @foreach ($columns as $col => $name)
                                                <td>{{ $name }}</td>
                                            @endforeach
                                            <td>
                                                Thao tác
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>
                                                    <div class="name"><span
                                                            class="maintitle">{{ $item->name }}</span></div>
                                                </td>

                                                <td>
                                                    <div class="slug"><span class="slug">{{ $item->slug }}</span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="image mr5">
                                                        <img src="{{ $item->logo ? asset('storage/' . $item->logo) : asset('backend/img/mvc_logo.png') }}"
                                                            alt="logo" width="100">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="description">{{ $item->description }}</div>
                                                </td>
                                                <td>
                                                    <div class="is_active">
                                                        {{ $item->is_active ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="created_at">{{ $item->created_at }}</div>
                                                </td>
                                                <td>
                                                    <div class="updated_at">{{ $item->updated_at }}</div>
                                                </td>

                                                <td class="text-center text-nowrap" style="width: 1px;">
                                                    <a href="{{ route($urlBase . 'edit', $item) }}"
                                                        class="btn btn-warning">
                                                        <i class="fa fa-edit"></i> Sửa
                                                    </a>
                                                    <a href="{{ route($urlBase . 'show', $item) }}"
                                                        class="btn btn-info">
                                                        <i class="fa fa-eye"></i> Xem
                                                    </a>
                                                    <form action="{{ route($urlBase . 'destroy', $item) }}"
                                                        method="post" id="item-{{ $item->id }}"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc muốn xoá?');">
                                                            <i class="fa fa-trash"></i> Xoá
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
