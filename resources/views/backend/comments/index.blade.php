<main id="main" class="main">
    @if (session()->has('success'))
        <div class="alert alert-{{ session()->get('success') ? 'info' : 'danger' }}">
            {{ session()->get('success') ? 'Thao tác thành công' : session()->get('error') }}
        </div>
    @endif

    <div class="pagetitle">
        <h1>Danh sách bình luận</h1>
        <nav>
            <ol class="breadcrumb"></ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Sản phẩm</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comments as $comment)
                                    <tr>
                                        <td>{{ $comment->id }}</td>
                                        <td>{{ $comment->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $comment->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                        <td>{{ $comment->content }}</td>
                                        <td>
                                            <span class="badge {{ $comment->status == 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $comment->status == 0 ? 'Hiển thị' : 'Ẩn' }}
                                            </span>
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <form action="{{ route('comments.toggleStatus', $comment->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-primary">
                                                    {{ $comment->status == 0 ? 'Ẩn' : 'Hiện' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $comments->links() }}
    </section>
</main>