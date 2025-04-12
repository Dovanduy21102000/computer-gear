<main id="content" role="main">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="{{ route('home.index') }}">Home</a></li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="mb-8">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.654009289495!2d105.84017247585846!3d21.006853888086725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc4ec1e1ebf%3A0x4c04b635b5d0922c!2zSOG6o2kgTuG7mWkgLSBIw6BvIE7hu5lpIFZp4buHdCBOYW0!5e0!3m2!1sen!2s!4v1711020000000" 
            width="100%" 
            height="514" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    

    <div class="container">
        <div class="row mb-10">
            <div class="col-md-8 col-xl-9">
                <div class="mr-xl-6">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title mb-0 pb-2 font-size-25">Gửi cho chúng tôi một tin nhắn</h3>
                    </div>
                    <p class="max-width-830-xl text-gray-90">
                        Hãy để lại tin nhắn của bạn, chúng tôi sẽ liên hệ lại sớm nhất có thể. Mọi ý kiến đóng góp của bạn đều rất quan trọng đối với chúng tôi.
                    </p>
                    <form action="{{ route('client.contacts.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Họ và tên --}}
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                    
                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                    
                            {{-- Số điện thoại --}}
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ old('phone', Auth::check() ? Auth::user()->phone ?? '' : '') }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                    
                            {{-- Chủ đề --}}
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Chủ đề</label>
                                    <input type="text" class="form-control" name="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                    
                            {{-- Nội dung tin nhắn --}}
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label">Nội dung tin nhắn <span class="text-danger">*</span></label>
                                    <textarea class="form-control p-5" rows="4" name="message" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    
                        {{-- Nút gửi --}}
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary-dark-w px-5">Gửi tin nhắn</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</main>
