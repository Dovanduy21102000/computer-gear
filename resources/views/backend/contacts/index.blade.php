<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="pagetitle">
        <h1>Quản lý liên hệ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý liên hệ</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách liên hệ</h5>
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-container">
                                <!-- Table with stripped rows -->
                                <table class="table datatable table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Số điện thoại</th>
                                            {{-- <th class="text-center">Nội dung</th> --}}
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contacts as $contact)
                                            <tr>
                                                 <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $contact->name }}</td>
                                                <td class="text-center">{{ $contact->email }}</td>
                                                <td class="text-center">{{ $contact->phone ?? 'Không có' }}</td>
                                                {{-- <td class="text-center">{{ Str::limit($contact->message, 50) }}</td> --}}
                                                <td class="text-center">
                                                    @if ($contact->status === 'pending')
                                                        <span class="badge bg-warning">Chờ xử lý</span>
                                                    @elseif ($contact->status === 'resolved')
                                                        <span class="badge bg-success">Đã xử lý</span>
                                                    @else
                                                        <span class="badge bg-danger">Spam</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('contacts.show', $contact->id) }}">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </a>
                                                    <a href="{{ route('contacts.edit', $contact->id) }}"><button
                                                        type="button" class="btn btn-warning"><i
                                                            class="bi bi-wrench"></i></button></a>
                                                    <form action="{{ route('contacts.destroy', $contact->id) }}" 
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Xóa liên hệ này?')">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- End Table -->
                            </div>
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
