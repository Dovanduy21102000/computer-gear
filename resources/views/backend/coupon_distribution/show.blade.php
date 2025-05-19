@php
    // Assume $availableUsers is passed from the controller or fetched via AJAX
@endphp
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Phân phối mã giảm giá</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('coupon-distribution.index') }}">Quản lý phân phối mã giảm
                        giá</a></li>
                <li class="breadcrumb-item active">Phân phối</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin mã giảm giá</h5>
                        <ul class="list-group mb-3">
                            <li class="list-group-item"><strong>Tên mã:</strong> {{ $coupon->name }}</li>
                            <li class="list-group-item"><strong>Mã:</strong> {{ $coupon->code }}</li>
                            <li class="list-group-item"><strong>Loại:</strong>
                                {{ $coupon->type === 'fixed' ? 'Cố định' : ($coupon->type === 'percent' || $coupon->type === 'percentage' ? 'Phần trăm' : $coupon->type) }}
                            </li>
                            <li class="list-group-item"><strong>Giá trị:</strong> {{ $coupon->price }}</li>
                            <li class="list-group-item"><strong>Số lượng:</strong> {{ $coupon->quantity }}</li>
                            <li class="list-group-item"><strong>Đã sử dụng:</strong> {{ $coupon->used_count }}</li>
                            <li class="list-group-item"><strong>Trạng thái:</strong> {!! $coupon->status
                                ? '<span class="badge bg-success">Kích hoạt</span>'
                                : '<span class="badge bg-danger">Ngừng</span>' !!}</li>
                            <li class="list-group-item"><strong>Ngày hết hạn:</strong> {{ $coupon->expire_date }}</li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Phân phối cho người dùng</h5>
                        <form action="{{ route('coupon-distribution.assign', $coupon->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user-search" class="form-label">Tìm kiếm người dùng:</label>
                                <input type="text" id="user-search" class="form-control"
                                    placeholder="Nhập tên hoặc email...">
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                <table class="table table-bordered table-hover align-middle" id="users-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="check-all"></th>
                                            <th>Tên</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($availableUsers ?? $assignedUsers->pluck('user') as $user)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="user_ids[]"
                                                        value="{{ $user->id }}"
                                                        {{ $assignedUsers->pluck('user_id')->contains($user->id) ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Lưu phân phối</button>
                                <a href="{{ route('coupon-distribution.index') }}" class="btn btn-secondary">Quay
                                    lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('user-search');
        const table = document.getElementById('users-table');
        const rows = table.querySelectorAll('tbody tr');
        const checkAll = document.getElementById('check-all');

        searchInput.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            rows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const email = row.children[2].textContent.toLowerCase();
                row.style.display = name.includes(value) || email.includes(value) ? '' : 'none';
            });
        });

        checkAll.addEventListener('change', function() {
            const checked = this.checked;
            rows.forEach(row => {
                const checkbox = row.querySelector('input[type="checkbox"]');
                if (row.style.display !== 'none') {
                    checkbox.checked = checked;
                }
            });
        });
    });
</script>
