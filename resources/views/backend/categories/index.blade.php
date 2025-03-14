<main id="main" class="main">
    @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif
      <div class="pagetitle">
        <h1>Danh sách danh mục</h1>
        <nav>
          <ol class="breadcrumb">
            <div class="col-sm-7">
              <div>
                  <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                      id="create-btn" data-bs-target="#showModal"><a
                          href="{{ route('admin.categories.create') }}" class="text-white">
                          <i class="ri-add-line align-bottom me-1"></i> Thêm Mới danh mục</button>
                  </a>
              </div>
          </div>
          </ol>
        </nav>
      </div><!-- End Page Title -->
  
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                
                <!-- Table with stripped rows -->
                <table class="table align-middle">
                  <thead class="table-white">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Danh mục cha</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Thao Tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
                        <td>
                            @if($category->is_active === 1)
                                <span class="badge bg-success text-white rounded-pill py-2 px-4">Hiện</span>
                            @else
                                <span class="badge bg-danger text-white rounded-pill py-2 px-4">Ẩn</span>
                            @endif
                        </td>                        
                        <td>{{ $category->created_at }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                          <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">sửa</a>
                            {{-- <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">delete</button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <!-- End Table -->
              </div>
            </div>
          </div>
        </div>
      </section>
  </main><!-- End #main -->
  