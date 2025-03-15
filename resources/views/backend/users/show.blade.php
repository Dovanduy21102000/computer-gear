<main id="main" class="main">

    <div class="pagetitle">
        <h1>Chi tiết thành viên</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin thành viên</h5>

                        <table class="table">
                            <tr>
                                <th>Tên:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Ảnh đại diện:</th>
                                <td>
                                    @if ($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" width="100">
                                    @else
                                        <span>Chưa có ảnh</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Ngày cập nhật:</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Chỉnh sửa</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa thành viên này?')">Xóa</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
