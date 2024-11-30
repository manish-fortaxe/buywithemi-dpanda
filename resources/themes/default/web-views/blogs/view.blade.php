@extends('layouts.front-end.app')

@section('title',translate('blogs'))

@push('css_or_js')
    <meta property="og:image" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="og:title" content="Blogs of {{$web_config['name']}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="twitter:title" content="Blogs of {{$web_config['name']}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <style>
        .blog-description * {
            font-size: 14px !important;
        }
    </style>


@endpush

@section('content')

<div class="container">

    <div class="row">
        <div class="col-8">
            <img src="{{ getStorageImages(path: $blog->media_full_url , type: 'backend-blog') }}" class="img-fluid" alt="Responsive image">
            <h4 class="fw-bold mt-5">{{ $blog->title }}</h4>
            <div class="mt-3 blog-description" >{!! $blog->description !!}</div>
        </div>
        <div class="col-4" >

        </div>
    </div>

</div>

@endsection

@push('script')


@endpush
