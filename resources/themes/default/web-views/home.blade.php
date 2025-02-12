@extends('layouts.front-end.app')

@section('title', $web_config['name']->value . ' ' . translate('online_Shopping') . ' | ' . $web_config['name']->value .
    ' ' . translate('ecommerce'))

    @push('css_or_js')
        <meta property="og:image" content="{{ $web_config['web_logo']['path'] }}" />
        <meta property="og:title" content="Welcome To {{ $web_config['name']->value }} Home" />
        <meta property="og:url" content="{{ env('APP_URL') }}">
        <meta property="og:description"
            content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)), 0, 160) }}">

        <meta property="twitter:card" content="{{ $web_config['web_logo']['path'] }}" />
        <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
        <meta property="twitter:url" content="{{ env('APP_URL') }}">
        <meta property="twitter:description"
            content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)), 0, 160) }}">

        <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/home.css') }}" async />
        <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/owl.carousel.min.css') }}" async>
        <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/owl.theme.default.min.css') }}" async>

        {{-- pulkit sir css --}}
        <style>
            .column img {
                margin-top: 0px;
                vertical-align: middle;
                width: 100%;
                border-radius: 5px;
                cursor: pointer;
                transition: 0.3s linear;
            }
        </style>
    @endpush

@section('content')
    <div class="__inline-61">
        @php($decimalPointSettings = !empty(getWebConfig(name: 'decimal_point_settings')) ? getWebConfig(name: 'decimal_point_settings') : 0)

        @if ($web_config['brand_setting'] && $brands->count() > 0)
            <section class="container-fluid rtl" style="margin-top: 10px;margin-bottom:10px;">
                <div class="brand-slider">
                    <div class="owl-carousel owl-theme brands-slider">
                        @foreach ($brands as $brand)
                            <div class="text-center">
                                <a href="{{ route('products', ['id' => $brand['id'], 'data_from' => 'brand', 'page' => 1]) }}"
                                    class="__brand-item">
                                    <img alt="{{ $brand->image_alt_text }}"
                                        src="{{ getStorageImages(path: $brand->image_full_url, type: 'brand') }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @include('web-views.partials._home-top-slider', ['main_banner' => $main_banner])

        {{-- @if ($web_config['featured_deals'] && count($web_config['featured_deals']) > 0)
            <section class="featured_deal">
                <div class="container">
                    <div class="__featured-deal-wrap bg--light">
                        <div class="d-flex flex-wrap justify-content-between gap-8 mb-3">
                            <div class="w-0 flex-grow-1">
                                <span
                                    class="featured_deal_title font-bold text-dark">{{ translate('featured_deal') }}</span>
                                <br>
                                <span
                                    class="text-left text-nowrap">{{ translate('see_the_latest_deals_and_exciting_new_offers') }}!</span>
                            </div>
                            <div>
                                <a class="text-capitalize view-all-text "
                                    href="{{ route('products', ['data_from' => 'featured_deal']) }}">
                                    {{ translate('view_all') }}
                                    <i
                                        class="czi-arrow-{{ Session::get('direction') === 'rtl' ? 'left mr-1 ml-n1 mt-1' : 'right ml-1' }}"></i>
                                </a>
                            </div>
                        </div>
                        <div class="owl-carousel owl-theme new-arrivals-product">
                            @foreach ($web_config['featured_deals'] as $key => $product)
                                @include('web-views.partials._product-card-1', [
                                    'product' => $product,
                                    'decimal_point_settings' => $decimalPointSettings,
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif --}}

        @if (isset($main_section_banner))
            <div class="container rtl pt-4 px-0 px-md-3">
                <a href="{{ $main_section_banner->url }}" target="_blank" class="cursor-pointer d-block">
                    <img class="d-block footer_banner_img __inline-63" alt=""
                        src="{{ getStorageImages(path: $main_section_banner->photo_full_url, type: 'wide-banner') }}"
                        loading='lazy'>
                </a>
            </div>
        @endif

        @if (count($footer_banner) > 1)
            <div class="container rtl pt-4ssss mt-3">
                <div class="promotional-banner-slider owl-carousel owl-theme">
                    @foreach ($footer_banner as $banner)
                        <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                            <img class="footer_banner_img __inline-63" alt=""
                                src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}">
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($footer_banner as $banner)
                    <div class="col-md-6">
                        <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                            <img class="footer_banner_img __inline-63" alt=""
                                src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}">
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($homeCategories->count() > 0)
            @foreach ($homeCategories as $category)
                @include('web-views.partials._category-wise-product', [
                    'decimal_point_settings' => $decimalPointSettings,
                ])
            @endforeach
        @endif

        @if ($blogs->count() > 0)
            <div class="container-fluid ">
                <div class="d-flex featuredproductwithbtn align-items-center">
                    <div class="feature-product-title">
                        <h3 class="m-0 color-black">{{ translate('latest_blogs') }}</h3>
                    </div>
                    <div class="viewallbtn ml-auto">
                        <a class="text-capitalize view-all-text" href="{{ route('blogs', ['page' => 1]) }}">
                            {{ translate('view_more') }}
                        </a>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        @foreach ($blogs as $blog)
                            @include('web-views.partials._inline-single-blog', ['blog' => $blog])
                        @endforeach
                    </div>
                </div>
            </div>
    </div>
    @endif

    <!-- 30 pixel hieght empty div -->
    <div class="empty-div" style="height: 30px;"></div>
    </div>

    <span id="direction-from-session" data-value="{{ session()->get('direction') }}"></span>
@endsection

@push('script')
    {{-- <script src="{{theme_asset(path: 'public/assets/front-end/js/owl.carousel.min.js')}}"></script> --}}
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/home.js') }}" defer></script>
@endpush
