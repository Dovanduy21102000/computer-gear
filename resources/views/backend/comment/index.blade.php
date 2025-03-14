@extends('backend.layout') 

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Quản lý bình luận</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->user->name ?? 'N/A' }}</td>
                    <td>{{ $comment->product->name ?? 'N/A' }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>
                        <form action="#" method="POST">
                            @csrf
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="0" {{ $comment->status == 0 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="1" {{ $comment->status == 1 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="#" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $comments->links() }}
    </div>
</div>
@endsection