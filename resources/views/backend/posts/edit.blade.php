<main id="main" class="main">

    <div class="pagetitle">
        <h1>Quản lý bài viết</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý bài viết</li>
                <li class="breadcrumb-item active">Chỉnh sửa bài viết</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $title }}</h5>

                        <!-- Edit Form -->
                        <form action="{{ route($urlBase . 'update', $post->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="row mb-3">
                                <label for="title" class="col-sm-2 col-form-label">Tiêu đề</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $post->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Slug -->
                            <div class="row mb-3">
                                <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $post->slug) }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Parent Category -->
                            <div class="row mb-3">
                                <label for="category_post_id" class="col-sm-2 col-form-label">Danh mục</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('category_post_id') is-invalid @enderror"
                                        id="category_post_id" name="category_post_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($category_post as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_post_id', $post->category_post_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_post_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thumbnail -->
                            <div class="row mb-3">
                                <label for="image" class="col-sm-2 col-form-label">Thumbnail</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('image') is-invalid @enderror" type="file"
                                        name="image" id="image">
                                    @if ($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image"
                                            width="100">
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Mô tả</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control @error('description') is-invalid @enderror" id="description"
                                        name="description" value="{{ old('description', $post->description) }}"
                                        required>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="row mb-3">
                                <label for="content" class="col-sm-2 col-form-label">Nội dung</label>
                                <div class="editor-container">
                                    <textarea id="ck_content" name="content" placeholder="CONTENT" class="form-control">{{ old('content', $post->content) }}</textarea>
                                    <style>
                                        .ck-editor__editable {
                                            min-height: 600px !important;
                                            max-height: 800px;
                                            /* Optional */
                                            height: auto !important;
                                        }
                                    </style>
                                    <script>
                                        CKEDITOR.ClassicEditor
                                            .create(document.querySelector('#ck_content'), {
                                                htmlSupport: {
                                                    allow: [{
                                                        name: /.*/,
                                                        attributes: true,
                                                        classes: true,
                                                        styles: true
                                                    }]
                                                },
                                                height: 400,
                                                allowedContent: true,
                                                extraAllowedContent: 'iframe[*]; div[*]; span[*]; style;',
                                                clipboard: {
                                                    pasteFilter: null
                                                },
                                                extraPlugins: ['MediaEmbed', 'Clipboard'],
                                                headers: {
                                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                                },
                                                toolbar: {
                                                    items: [
                                                        'undo', 'redo', '|',
                                                        'heading', '|',
                                                        'bold', 'italic', 'removeFormat', '|',
                                                        'bulletedList', 'numberedList', '|',
                                                        'fontSize', 'fontFamily', '|',
                                                        'alignment', '|',
                                                        'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed'
                                                    ],
                                                    shouldNotGroupWhenFull: true,
                                                },
                                                mediaEmbed: {
                                                    previewsInData: true
                                                },
                                                fontFamily: {
                                                    options: ['TiemposHeadline', 'TiemposText', 'SansSerifFont', 'Arial', 'Courier New', 'Georgia',
                                                        'Times New Roman', 'Verdana'
                                                    ],
                                                    supportAllValues: true
                                                },
                                                ckfinder: {
                                                    uploadUrl: "{{ route('posts.upload') }}",
                                                    options: {
                                                        resourceType: "Images"
                                                    }
                                                },
                                                removePlugins: ['AIAssistant', 'MathType', 'PasteFromOfficeEnhanced']
                                            })
                                            .then(createdEditor => {
                                                editor = createdEditor;
                                            })
                                            .catch(error => {
                                                console.error('Error initializing CKEditor:', error);
                                            });
                                    </script>
                                    <script>
                                        CKEDITOR.ClassicEditor
                                            .create(document.querySelector('#ck_content'), {
                                                htmlSupport: {
                                                    allow: [{
                                                            name: /.*/, // Allows all element
                                                            attributes: true, // Allows all attributes
                                                            classes: true, // Allows all classes
                                                            styles: true // Allows all inline-css styles
                                                        },

                                                    ]
                                                },
                                                height: 400,
                                                allowedContent: true,
                                                extraAllowedContent: 'iframe[*]; div[*]; span[*]; style;',
                                                // Make sure clipboard and pasting work
                                                clipboard: {
                                                    pasteFilter: null, // Allow all pasted content
                                                },
                                                extraPlugins: [
                                                    'MediaEmbed', 'Clipboard'
                                                ],
                                                headers: {
                                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                                },
                                                toolbar: {
                                                    items: [
                                                        'undo', 'redo', '|',
                                                        'findAndReplace', 'selectAll', '|',
                                                        'heading', '|',
                                                        'bold', 'italic', 'removeFormat', '|',
                                                        'bulletedList', 'numberedList', '|',
                                                        'fontSize', 'fontFamily', '|',
                                                        'alignment', '-', 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                                                        '|',
                                                        'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed'
                                                    ],
                                                    shouldNotGroupWhenFull: true,
                                                },
                                                mediaEmbed: {
                                                    previewsInData: true // Ensure embedded media (like YouTube) shows a preview in the editor
                                                },

                                                fontFamily: {
                                                    options: [
                                                        'TiemposHeadline',
                                                        'TiemposText',
                                                        'SansSerifFont',
                                                        'Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana'
                                                    ],
                                                    supportAllValues: true
                                                },

                                                ckfinder: {
                                                    uploadUrl: "{{ route('posts.upload') }}",
                                                    options: {
                                                        resourceType: "Images"
                                                    }
                                                },
                                                heading: {
                                                    options: [{
                                                            model: 'paragraph',
                                                            title: 'Paragraph',
                                                            class: 'ck-heading_paragraph'
                                                        },
                                                        {
                                                            model: 'heading1',
                                                            view: 'h1',
                                                            title: 'Heading 1',
                                                            class: 'ck-heading_heading1'
                                                        },
                                                        {
                                                            model: 'heading2',
                                                            view: 'h2',
                                                            title: 'Heading 2',
                                                            class: 'ck-heading_heading2'
                                                        },
                                                        {
                                                            model: 'heading3',
                                                            view: 'h3',
                                                            title: 'Heading 3',
                                                            class: 'ck-heading_heading3'
                                                        },
                                                        {
                                                            model: 'heading4',
                                                            view: 'h4',
                                                            title: 'Heading 4',
                                                            class: 'ck-heading_heading4'
                                                        },
                                                        {
                                                            model: 'heading5',
                                                            view: 'h5',
                                                            title: 'Heading 5',
                                                            class: 'ck-heading_heading5'
                                                        },
                                                        {
                                                            model: 'heading6',
                                                            view: 'h6',
                                                            title: 'Heading 6',
                                                            class: 'ck-heading_heading6'
                                                        }
                                                    ]
                                                },
                                                fontSize: {
                                                    options: [10, 12, 14, 'default', 18, 20, 22],
                                                    supportAllValues: true
                                                },
                                                image: {
                                                    styles: [
                                                        'alignLeft', 'alignCenter', 'alignRight'
                                                    ],
                                                    toolbar: [
                                                        'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight', '|',
                                                        'imageTextAlternative'
                                                    ]
                                                },
                                                removePlugins: [
                                                    // These two are commercial, but you can try them out without registering to a trial.
                                                    // 'ExportPdf',
                                                    // 'ExportWord',
                                                    'AIAssistant',
                                                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                                                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                                                    // Storing images as Base64 is usually a very bad idea.
                                                    // Replace it on production website with other solutions:
                                                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                                                    // 'Base64UploadAdapter',
                                                    'MultiLevelList',
                                                    'RealTimeCollaborativeComments',
                                                    'RealTimeCollaborativeTrackChanges',
                                                    'RealTimeCollaborativeRevisionHistory',
                                                    'PresenceList',
                                                    'Comments',
                                                    'TrackChanges',
                                                    'TrackChangesData',
                                                    'RevisionHistory',
                                                    'Pagination',
                                                    'WProofreader',
                                                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                                                    // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                                                    'MathType',
                                                    // The following features require additional license.
                                                    'SlashCommand',
                                                    'Template',
                                                    'DocumentOutline',
                                                    'FormatPainter',
                                                    'TableOfContents',
                                                    'PasteFromOfficeEnhanced',
                                                    'CaseChange'
                                                ],

                                            })
                                            .then(createdEditor => {
                                                editor = createdEditor;
                                                if (editor.filter.check('iframe')) {
                                                    console.log('Iframe is allowed!');
                                                } else {
                                                    console.log('Iframe is not allowed!');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error initializing CKEditor:', error);
                                            });
                                    </script>
                                </div>
                            </div>

                            <!-- Active Checkbox -->
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Kích hoạt</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input type="hidden" name="status" value="0">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="1" {{ old('status', $post->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Có</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Hot Checkbox -->
                            <div class="row mb-3">
                                <label for="is_hot" class="col-sm-2 col-form-label">Nổi bật</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input type="hidden" name="is_hot" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_hot"
                                            name="is_hot" value="1"
                                            {{ old('is_hot', $post->is_hot) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_hot">Có</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <a href="{{ route($urlBase . 'index') }}" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </div>

                        </form>
                        <!-- End Edit Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
