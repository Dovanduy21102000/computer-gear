<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-primary">Cập nhật liên hệ</h1>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center text-uppercase">Chỉnh sửa thông tin liên hệ</h5>

                        <!-- Hiển thị thông báo -->
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Tên</label>
                                <input type="text" name="name" class="form-control" value="{{ $contact->name }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $contact->email }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ $contact->phone }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">Chủ đề</label>
                                <input type="text" name="subject" class="form-control"
                                    value="{{ $contact->subject }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold">Nội dung</label>
                                <textarea name="message" class="form-control" rows="4" disabled>{{ $contact->message }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}>Chờ xử
                                        lý</option>
                                    <option value="resolved" {{ $contact->status == 'resolved' ? 'selected' : '' }}>Đã
                                        xử lý</option>
                                    <option value="spam" {{ $contact->status == 'spam' ? 'selected' : '' }}>Spam
                                    </option>
                                </select>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('contacts.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Cập nhật liên hệ
                                </button>
                            </div>
                        </form><!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
