 @extends('layouts.back-end.app')

@section('title', translate('blog'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                    {{ translate('blog_update_form') }}
                </h2>
            </div>
            <div>
                <a class="btn btn--primary text-white" href="{{ route('admin.blog.list') }}">
                    <i class="tio-chevron-left"></i> {{ translate('back') }}</a>
            </div>
        </div>

        <div class="row text-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.blog.update', [$blog['id']]) }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <input type="hidden" id="id" name="id">
                                    <div class="form-group mb-3" id="title">
                                        <label for="title" class="title-color text-capitalize">{{ translate('title') }}</label>
                                        <input type="text" name="title" class="form-control" id="title" placeholder="{{ translate('Enter_title') }}" value="{{ $blog['title'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">
                                            {{ translate('category_type') }}
                                        </label>
                                        <select class="js-example-responsive form-control w-100" name="category_id" required id="blog_type_select">
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}" {{ $blog['category_id'] == $category->id ? 'selected' : '' }} >{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="meta_title">
                                        <label for="meta_title" class="title-color text-capitalize">{{ translate('meta_title') }}</label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title" placeholder="{{ translate('Enter_meta_title') }}" value="{{ $blog['meta_title'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                    <div>
                                        <div class="mx-auto text-center">
                                            <div class="uploadDnD">
                                                <div class="form-group inputDnD input_image" data-title="{{ 'Drag and drop file or Browse file' }}">
                                                    <input type="file" name="media" class="form-control-file text--primary font-weight-bold" id="blog" accept=".jpg, .png, .jpeg, .gif, .bmp, .webp |image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="name" class="title-color text-capitalize">
                                            {{ translate('blog_media') }}
                                        </label>
                                        <span class="title-color" id="theme_ratio">( {{ translate('ratio') }} 4:1 )</span>
                                        <p>{{ translate('blog_Image_ratio_is_not_same_for_all_sections_in_website') }}. {{ translate('please_review_the_ratio_before_upload') }}</p>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3" id="meta_description">
                                        <label for="meta_description" class="title-color text-capitalize">{{ translate('meta_description') }}</label>
                                        <textarea class="form-control" name="meta_description">{{ $blog['meta_description'] }}</textarea>
                                    </div>
                                    <div class="form-group mb-3" id="description">
                                        <label for="description" class="title-color text-capitalize">{{ translate('description') }}</label>
                                        <textarea class="summernote" name="description">{{ $blog['description'] }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ dynamicAsset(path: 'public/assets/back-end/js/blog.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.js') }}"></script>

<script>
$(document).on('ready', function () {
    $('.summernote').summernote({
        'height': 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ],
    });

});
</script>
@endpush
