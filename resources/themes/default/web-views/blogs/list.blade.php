@extends('layouts.front-end.app')

@section('title', translate('blogs'))

@push('css_or_js')
@endpush

@section('content')

<div class="container">
    <div class="row">
        @foreach($blogs as $blog)
            @include('web-views.partials._inline-single-blog',['blog'=>$blog])
        @endforeach
    </div>
</div>



@endsection

@push('script')

@endpush
