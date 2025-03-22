<main id="main" class="main">
    <div class="pagetitle">
        <h1>Chi tiết Liên Hệ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Danh sách Liên Hệ</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Thông tin Liên Hệ</h5>

                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $contact->id }}</td>
                            </tr>
                            <tr>
                                <th>Họ và Tên</th>
                                <td>{{ $contact->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $contact->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td>{{ $contact->phone ?? 'Không có' }}</td>
                            </tr>
                            <tr>
                                <th>Chủ đề</th>
                                <td>{{ $contact->subject ?? 'Không có' }}</td>
                            </tr>
                            <tr>
                                <th>Nội dung</th>
                                <td>{{ $contact->message }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <span class="badge {{ $contact->status == 'pending' ? 'bg-warning' : ($contact->status == 'resolved' ? 'bg-success' : 'bg-danger') }}">
                                        {{ $contact->status == 'pending' ? 'Chờ xử lý' : ($contact->status == 'resolved' ? 'Đã xử lý' : 'Spam') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày gửi</th>
                                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('contacts.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left-circle"></i> Quay lại
                        </a>

                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                                <i class="bi bi-trash-fill"></i> Xóa
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
