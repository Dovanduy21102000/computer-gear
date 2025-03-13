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
          </ol>
        </nav>
      </div><!-- End Page Title -->
  
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                
                <!-- Table with stripped rows -->
                <table class="table datatable">
                  <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Danh mục cha</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
                        <td class="text-center">
                            @if($category->status = 1)
                                <span class="badge bg-success text-white rounded-pill py-2 px-4">Hiện</span>
                            @else
                                <span class="badge bg-danger text-white rounded-pill py-2 px-4">Ẩn</span>
                            @endif
                        </td>                        
                        <td>{{ $category->created_at }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            <a href="" class="btn btn-primary">sửa</a>
                            <form action="" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">delete</button>
                            </form>
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
  