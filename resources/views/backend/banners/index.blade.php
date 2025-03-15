<main id="main" class="main">
    @if (session()->has('success') && !session()->get('success'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if (session()->has('success') && session()->get('success'))
        <div class="alert alert-info">
            Thao tac thanh cong
        </div>
    @endif
    <div class="pagetitle">
        <h1>Danh sách banner</h1>
        <nav>
            <ol class="breadcrumb">
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <a class="btn btn-primary mb-10" style="float " href="{{ route('banners.create') }}">Them moi</a>
 
    <br>
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
                                    <th>Tiêu đề</th>
                                    <th>Ảnh</th>
                                    {{-- <th>Btn url</th>
                                    <th>Mô tả</th> --}}
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $banner)
                                    <tr>
                                        <td>{{ $banner->id }}</td>
                                        <td>{{ $banner->title }}</td>
                                        <td>
                                            @if ($banner->image)
                                                <img src="{{ Storage::url($banner->image) }}" width="150px" height="100px">
                                            @endif
                                        </td>
                                        {{-- <td>
    
                                            @if ($banner->btn_url)
                                                <a href="{{ $banner->btn_url }}" target="_blank">{{ $banner->btn_url }}</a>
                                            @else
                                                <span class="text-muted">Không có URL</span>
                                            @endif
                                        
                                    </td>
                                        
                                        <td>{{ $banner->description }}</td> --}}
                                        <td>
                                            <span class="badge {{ $banner->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $banner->status ? 'Hoạt động' : 'Ngừng' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('banners.show', $banner->id) }}" ><button type="button" class="btn btn-success"><i class="bi bi-eye"></i></button></a>
                                            <a href="{{ route('banners.edit', $banner->id) }}"
                                                ><button type="button" class="btn btn-warning"><i class="bi bi-wrench"></i></button></a>
                                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Xóa quảng cáo này?')"><i class="bi bi-trash-fill"></i></button>
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
           {{$banners->links()}}
    </section>
  
</main><!-- End #main -->

