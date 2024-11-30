@extends('layouts.back-end.app')

@section('title', translate('blog'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/blog.png') }}" alt="">
                {{ translate('blog_Section') }}
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>


                <div class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg-30">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/note.png') }}" alt="">
                        <h5 class="text-primary mb-0">{{ translate('note') }}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('currently_you_are_managing_blogs_for') }} {{ucwords(str_replace("_", " ", theme_root_path())) }}.{{ translate('these_saved_data_is_only_applicable_only_for_') }}{{ucwords(str_replace("_", " ", theme_root_path())) }}.{{ translate('if_you_change_theme_from_theme_setup_these_blogs_will_not_be_shown_in_changed_theme._You_have_upload_all_the_blogs_over_again _according_to_the_new_theme_ratio_and_sizes._If_you_switch_back_to_') }}{{ucwords(str_replace("_", " ", theme_root_path())) }}{{ translate('_again_,_you_will_see_the_saved_data.') }}</p>
                </div>
            </div>
        </div>

        <div class="row pb-4 d--none text-start" id="main-blog">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ translate('blog_form') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.blog.store') }}" method="post" enctype="multipart/form-data"
                              class="blog_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="hidden" id="id" name="id">
                                    <div class="form-group mb-3" id="title">
                                        <label for="title" class="title-color text-capitalize">{{ translate('title') }}</label>
                                        <input type="text" name="title" class="form-control" id="title" placeholder="{{ translate('Enter_title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">
                                            {{ translate('category_type') }}
                                        </label>
                                        <select class="js-example-responsive form-control w-100" name="category_id" required id="blog_type_select">
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="meta_title">
                                        <label for="meta_title" class="title-color text-capitalize">{{ translate('meta_title') }}</label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title" placeholder="{{ translate('Enter_meta_title') }}">
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
                                        <textarea class="form-control" name="meta_description">{{ old('meta_description') }}</textarea>
                                    </div>
                                    <div class="form-group mb-3" id="description">
                                        <label for="description" class="title-color text-capitalize">{{ translate('description') }}</label>
                                        <textarea class="summernote" name="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4" type="reset">{{ translate('reset') }}</button>
                                    <button id="add" type="submit"
                                            class="btn btn--primary px-4">{{ translate('save') }}</button>
                                    <button id="update"
                                       class="btn btn--primary d--none text-white">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="blog-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ translate('blog_table') }}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12">{{ $blogs->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div class="row gy-2 gx-2 align-items-center text-left">
                                    <div class="col-sm-12 col-md-9">
                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="row gy-2 gx-2 align-items-center text-left">
                                                <div class="col-sm-12 col-md-9">
                                                    <select class="form-control __form-control" name="searchValue" id="date_type">
                                                        <option value="">{{ translate('all') }}</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category['id'] }}" {{ request('searchValue') == $category['id'] ? 'selected':'' }}>{{ $category['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-3">
                                                    <button type="submit" class="btn btn--primary px-4 w-100 text-nowrap">
                                                        {{ translate('filter') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <div id="blog-btn">
                                            <button id="main-blog-add" class="btn btn--primary text-nowrap text-capitalize">
                                                <i class="tio-add"></i>
                                                {{ translate('add_blog') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="pl-xl-5">{{ translate('SL') }}</th>
                                <th>{{ translate('media') }}</th>
                                <th>{{ translate('title') }}</th>
                                <th>{{ translate('category') }}</th>
                                <th>{{ translate('status') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            @foreach($blogs as $key=>$blog)
                                <tbody>
                                <tr id="data-{{ $blog->id}}">
                                    <td class="pl-xl-5">{{ $blogs->firstItem()+$key}}</td>
                                    <td>
                                        <img class="ratio-4:1" width="120" height="50" alt=""
                                             src="{{ getStorageImages(path: $blog->media_full_url , type: 'backend-blog') }}">
                                    </td>
                                    <td>{{ $blog->title }}</td>
                                    <td>{{ $blog->category->name }}</td>
                                    <td>
                                        <form action="{{ route('admin.blog.status') }}" method="post" id="blog-status{{ $blog['id'] }}-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $blog['id'] }}">
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                       id="blog-status{{ $blog['id'] }}" value="1" {{ $blog['status'] == 1 ? 'checked' : '' }}
                                                       data-modal-id="toggle-status-modal"
                                                       data-toggle-id="blog-status{{ $blog['id'] }}"
                                                       data-on-image="blog-status-on.png"
                                                       data-off-image="blog-status-off.png"
                                                       data-on-title="{{ translate('Want_to_Turn_ON').' '.translate(str_replace('_',' ',$blog->blog_type)).' '.translate('status') }}"
                                                       data-off-title="{{ translate('Want_to_Turn_OFF').' '.translate(str_replace('_',' ',$blog->blog_type)).' '.translate('status') }}"
                                                       data-on-message="<p>{{ translate('if_enabled_this_blog_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message="<p>{{ translate('if_disabled_this_blog_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.blog.update',[$blog['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm cursor-pointer blog-delete-button"
                                               title="{{ translate('delete') }}"
                                               id="{{ $blog['id'] }}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $blogs->links() }}
                        </div>
                    </div>

                    @if(count($blogs)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_blog_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="route-admin-blog-store" data-url="{{ route('admin.blog.store') }}"></span>
    <span id="route-admin-blog-delete" data-url="{{ route('admin.blog.delete') }}"></span>
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
        callbacks: {
            onChange: function (contents, $editable) {
                if ($(this).hasClass('product-description-default-language')) {
                    var textWithoutTagsAndEntities = contents.replace(/<[^>]+>|&[^;]+;/g, '');
                    var maxLength = 160;
                    if (textWithoutTagsAndEntities.length > maxLength) {
                        textWithoutTagsAndEntities = textWithoutTagsAndEntities.substring(0, maxLength);
                    }
                    $('#meta_description').val(textWithoutTagsAndEntities);
                }
            }
        }
    });

});
</script>
@endpush
