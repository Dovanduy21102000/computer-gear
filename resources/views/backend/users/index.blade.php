<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý thành viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý thành viên</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách thành viên</h5>
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-top">
                                <div >
                                    <a class="btn btn-primary" href="{{ route('users.create') }}">Thêm mới</a>
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
                                            <th class="text-center">Ảnh đại diện</th>
                                            <th class="text-center">Họ và tên</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Số điện thoại</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    @if ($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" width="50" height="50">

                                                    @else
                                                        <span>Không có ảnh</span>
                                                    @endif
                                                </td>

                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    @if ($user->status == 'active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @elseif ($user->status == 'inactive')
                                                        <span class="badge bg-warning">Không hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger">Bị khóa</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('users.show', $user->id) }}"
                                                        class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-warning"><i class="fa fa-edit"></i></a>

                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bạn có chắc muốn xóa?')"><i
                                                                class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $users->links() }}
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
