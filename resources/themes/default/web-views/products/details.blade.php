@extends('layouts.front-end.app')

@section('title', $product['name'])

@push('css_or_js')
    @include(VIEW_FILE_NAMES['product_seo_meta_content_partials'], [
        'metaContentData' => $product?->seoInfo,
        'product' => $product,
    ])
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/product-details.css') }}" />
    <script defer src="https://jssdk.payu.in/widget/affordability-widget.min.js"></script>

@endpush

@section('content')
    <div class="__inline-23 font-helvetica">
        <div class="rtl text-align-direction">
            <div class="product-wraper-single {{ Session::get('direction') === 'rtl' ? '__dir-rtl' : '' }}">
                <div class="wrappertop-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-md-4 col-12">
                                <div class="cz-product-gallery">
                                    <div class="cz-preview">
                                        <div id="sync1" class="owl-carousel owl-theme product-thumbnail-slider">
                                            @if ($product->images != null && json_decode($product->images) > 0)
                                                @if (json_decode($product->colors) && count($product->color_images_full_url) > 0)
                                                    @foreach ($product->color_images_full_url as $key => $photo)
                                                        @if ($photo['color'] != null)
                                                            <div class="product-preview-item d-flex align-items-center justify-content-center {{ $key == 0 ? 'active' : '' }}"
                                                                id="image{{ $photo['color'] }}">
                                                                <img class="cz-image-zoom img-responsive w-100"
                                                                    src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                    data-zoom="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                    alt="{{ translate('product') }}" width="">
                                                                <div class="cz-image-zoom-pane"></div>
                                                            </div>
                                                        @else
                                                            <div class="product-preview-item d-flex align-items-center justify-content-center {{ $key == 0 ? 'active' : '' }}"
                                                                id="image{{ $key }}">
                                                                <img class="cz-image-zoom img-responsive w-100"
                                                                    src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                    data-zoom="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                    alt="{{ translate('product') }}" width="">
                                                                <div class="cz-image-zoom-pane"></div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach ($product->images_full_url as $key => $photo)
                                                        <div class="product-preview-item d-flex align-items-center justify-content-center {{ $key == 0 ? 'active' : '' }}"
                                                            id="image{{ $key }}">
                                                            <img class="cz-image-zoom img-responsive w-100"
                                                                src="{{ getStorageImages($photo, type: 'product') }}"
                                                                data-zoom="{{ getStorageImages(path: $photo, type: 'product') }}"
                                                                alt="{{ translate('product') }}" width="">
                                                            <div class="cz-image-zoom-pane"></div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-3">

                                    </div>

                                    <div class="cz __y-15">
                                        <div class="table-responsive __max-h-515px" data-simplebar>
                                            <div class="d-flex">
                                                <div id="sync2" class="owl-carousel owl-theme product-thumb-slider image-gap">
                                                    @if ($product->images != null && json_decode($product->images) > 0)
                                                        @if (json_decode($product->colors) && count($product->color_images_full_url) > 0)
                                                            @foreach ($product->color_images_full_url as $key => $photo)
                                                                @if ($photo['color'] != null)
                                                                    <div class="">
                                                                        <a class="product-preview-thumb color-variants-preview-box-{{ $photo['color'] }} {{ $key == 0 ? 'active' : '' }} d-flex align-items-center justify-content-center"
                                                                            id="preview-img{{ $photo['color'] }}"
                                                                            href="#image{{ $photo['color'] }}">
                                                                            <img alt="{{ translate('product') }}"
                                                                                src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}">
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="">
                                                                        <a class="product-preview-thumb {{ $key == 0 ? 'active' : '' }} d-flex align-items-center justify-content-center"
                                                                            id="preview-img{{ $key }}"
                                                                            href="#image{{ $key }}">
                                                                            <img alt="{{ translate('product') }}"
                                                                                src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}">
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach ($product->images_full_url as $key => $photo)
                                                                <div class="">
                                                                    <a class="product-preview-thumb {{ $key == 0 ? 'active' : '' }} d-flex align-items-center justify-content-center"
                                                                        id="preview-img{{ $key }}"
                                                                        href="#image{{ $key }}">
                                                                        <img alt="{{ translate('product') }}"
                                                                            src="{{ getStorageImages(path: $photo, type: 'product') }}">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 col-md-8 col-12 mt-md-0 mt-sm-3 web-direction">
                                <div class="productsingle">
                                    <div class="product-brandname">
                                        <p>{{ $product->brand->name }}</p>
                                    </div>
                                    <!-- <div class="d-flex productshare-share-product-title">
                                        <div class="sharethis-inline-share-buttons share--icons text-align-direction">
                                        </div>
                                    </div> -->
                                    <span class="mb-2 __inline-24 productnametext">{{ $product->name }}</span>
                                    {{-- <div class="productshortinfo"><p>(6GB RAM, 128GB, Black Diamond)</p></div> --}}
                                    <div class="productsku-details">
                                        <p>SKU: {{ $product->code ?? '' }}</p>
                                    </div>
                                    @if ($product->origin)
                                        <div class="countryinfo">
                                            <p>Country of Origin: {{ ucfirst($product->origin) }}</p>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <span
                                            class="font-weight-normal text-primary-color d-flex align-items-end gap-2 font-26">
                                            {!! getPriceRangeWithDiscount(product: $product) !!}
                                        </span>
                                        <div class="taxpricetext">
                                            <p>(Inclusive of all taxes)</p>
                                        </div>

                                        <div id="payuWidget" class="mt-5"> </div>

                                    </div>

                                    <form id="add-to-cart-form" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        @foreach (json_decode($product->choice_options) as $key => $choice)
                                            <div class="storagetab align-items-center">
                                                <div
                                                    class="product-description-label storage text-dark font-bold {{ Session::get('direction') === 'rtl' ? 'pl-2' : 'pr-2' }} text-capitalize">
                                                    {{ $choice->title }}
                                                    :
                                                </div>
                                                <div>
                                                    <div
                                                        class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-0 flex-start row ps-0">
                                                        @foreach ($choice->options as $index => $option)
                                                            <div>
                                                                <div class="for-mobile-capacity">
                                                                    <input type="radio"
                                                                        id="{{ str_replace(' ', '', $choice->name . '-' . $option) }}"
                                                                        name="{{ $choice->name }}"
                                                                        value="{{ $option }}"
                                                                        @if ($index == 0) checked @endif>
                                                                    <label class="__text-12px"
                                                                        for="{{ str_replace(' ', '', $choice->name . '-' . $option) }}"">{{ $option }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div
                                            class="position-relative {{ Session::get('direction') === 'rtl' ? 'ml-n4' : 'mr-n4' }} ">
                                            @if (count(json_decode($product->colors)) > 0)
                                                <div class="align-items-center colorselection">
                                                    <div class="product-description-label m-0 text-dark font-bold">
                                                        {{ translate('color') }}
                                                        :
                                                    </div>
                                                    <div>
                                                        <ul class="list-inline checkbox-color mb-0 flex-start ps-0">
                                                            @foreach (json_decode($product->colors) as $key => $color)
                                                                <li>
                                                                    <input type="radio"
                                                                        id="{{ str_replace(' ', '', $product->id . '-color-' . str_replace('#', '', $color)) }}"
                                                                        name="color" value="{{ $color }}"
                                                                        @if ($key == 0) checked @endif>
                                                                    <label style="background: {{ $color }};"
                                                                        class="focus-preview-image-by-color shadow-border"
                                                                        for="{{ str_replace(' ', '', $product->id . '-color-' . str_replace('#', '', $color)) }}"
                                                                        data-toggle="tooltip"
                                                                        data-key="{{ str_replace('#', '', $color) }}"
                                                                        data-colorid="preview-box-{{ str_replace('#', '', $color) }}"
                                                                        data-title="{{ \App\Utils\get_color_name($color) }}">
                                                                        <span class="outline"></span></label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                                $qty = 0;
                                                if (!empty($product->variation)) {
                                                    foreach (json_decode($product->variation) as $key => $variation) {
                                                        $qty += $variation->qty;
                                                    }
                                                }
                                            @endphp
                                        </div>
                                        @if (isset($product['features']) && count(json_decode($product['features'])) > 0)
                                            <div class="keyfeatures-wrapper">
                                                <div class="keypointscontent">
                                                    <div class="keyheadingtext">
                                                        <h2 class="keyheading">key features</h2>
                                                    </div>
                                                    <div class="ourproductkeypoints">
                                                        <ul class="itemkeypints">
                                                            @foreach (json_decode($product['features']) as $feature)
                                                                @foreach ($feature as $key => $value)
                                                                    <li>{{ $value }}</li>
                                                                @endforeach
                                                            @endforeach
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        @php($extensionIndex = 0)
                                        @if (
                                            $product['product_type'] == 'digital' &&
                                                $product['digital_product_file_types'] &&
                                                count($product['digital_product_file_types']) > 0 &&
                                                $product['digital_product_extensions']
                                        )
                                            @foreach ($product['digital_product_extensions'] as $extensionKey => $extensionGroup)
                                                <div class="row flex-start
										mb-1">
                                                    <div
                                                        class="product-description-label text-dark font-bold {{ Session::get('direction') === 'rtl' ? 'pl-2' : 'pr-2' }} text-capitalize mb-2">
                                                        {{ translate($extensionKey) }} :
                                                    </div>

                                                    <div>
                                                        @if (count($extensionGroup) > 0)
                                                            <div
                                                                class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-0 flex-start row ps-0">
                                                                @foreach ($extensionGroup as $index => $extension)
                                                                    <div>
                                                                        <div class="for-mobile-capacity">
                                                                            <input type="radio" hidden
                                                                                id="extension_{{ str_replace(' ', '-', $extension) }}"
                                                                                name="variant_key"
                                                                                value="{{ $extensionKey . '-' . preg_replace('/\s+/', '-', $extension) }}"
                                                                                {{ $extensionIndex == 0 ? 'checked' : '' }}>
                                                                            <label
                                                                                for="extension_{{ str_replace(' ', '-', $extension) }}"
                                                                                class="__text-12px">
                                                                                {{ $extension }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @php($extensionIndex++)
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif



                                        <div class="mt-3">
                                            <div class="quantitybox product-quantity d-flex flex-column __gap-15">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-description-label text-dark font-bold mt-0">
                                                        {{ translate('quantity') }} :
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center quantity-box border rounded web-text-primary">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-number __p-10" type="button"
                                                                data-type="minus" data-field="quantity"
                                                                disabled="disabled">
                                                                -
                                                            </button>
                                                        </span>
                                                        <input type="text" name="quantity"
                                                            class="input-number text-center cart-qty-field __inline-29 border-0 "
                                                            placeholder="{{ translate('1') }}"
                                                            value="{{ $product->minimum_order_qty ?? 1 }}"
                                                            data-producttype="{{ $product->product_type }}"
                                                            min="{{ $product->minimum_order_qty ?? 1 }}"
                                                            max="{{ $product['product_type'] == 'physical' ? $product->current_stock : 100 }}">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-number __p-10" type="button"
                                                                data-producttype="{{ $product->product_type }}"
                                                                data-type="plus" data-field="quantity">
                                                                +
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" class="product-generated-variation-code"
                                                        name="product_variation_code">
                                                    <input type="hidden" value=""
                                                        class="in_cart_key form-control w-50" name="key">
                                                </div>
                                                <!-- <div id="chosen_price_div">
                                                        <div
                                                            class="d-none d-sm-flex justify-content-start align-items-center me-2">
                                                            <div
                                                                class="product-description-label text-dark font-bold text-capitalize">
                                                                <strong>{{ translate('total_price') }}</strong> :
                                                            </div>
                                                            &nbsp; <strong id="chosen_price" class="text-base"></strong>
                                                            <small
                                                                class="ms-2 font-regular">
                                                                (<small>{{ translate('tax') }} : </small>
                                                                <small id="set-tax-amount"></small>)
                                                            </small>
                                                        </div>
                                                    </div> -->
                                            </div>
                                        </div>
                                </div>




                                <div class="row no-gutters d-none flex-start d-flex">
                                    <div class="col-12">
                                        @if ($product['product_type'] == 'physical')
                                            <h5 class="text-danger out-of-stock-element d--none">
                                                {{ translate('out_of_stock') }}</h5>
                                        @endif
                                    </div>
                                </div>
                                </form>

                            </div>
                        </div>
                    </div>



                    <div class="wrapper-product-action">
                        <div class="x-contianer">
                            <div class="row">
                                <div class="d-flex innerwrapper-content w-100">
                                    <div class="leftwrappercontent">
                                        <div class="d-flex imagewithtitletext">
                                            <div class="product-thumb-stickybar">
                                                <img src="{{ asset($product->thumbnail_full_url['path']) }}"
                                                    alt="product image" class="productthumbnail">
                                            </div>
                                            <div class="product-title-price">
                                                <div class="producttitle">
                                                    <h2 class="productname">{{ $product->name }}</h2>
                                                </div>
                                                <div class="productprice">

                                                    <span
                                                        class="price-product font-weight-normal fs-14 text-accent d-flex align-items-end gap-2">
                                                        {!! getPriceRangeWithDiscount(product: $product) !!}
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="__btn-grp d-none d-sm-flex">
                                        @if (
                                            ($product->added_by == 'seller' &&
                                                ($sellerTemporaryClose ||
                                                    (isset($product->seller->shop) &&
                                                        $product->seller->shop->vacation_status &&
                                                        $currentDate >= $sellerVacationStartDate &&
                                                        $currentDate <= $sellerVacationEndDate))) ||
                                                ($product->added_by == 'admin' &&
                                                    ($inHouseTemporaryClose ||
                                                        ($inHouseVacationStatus &&
                                                            $currentDate >= $inHouseVacationStartDate &&
                                                            $currentDate <= $inHouseVacationEndDate))))
                                            <button class="btn btn-style-black string-limit" type="button" disabled>
                                                {{ translate('add_to_cart') }}
                                            </button>
                                            <button class="btn btn-style-black" type="button" disabled>
                                                {{ translate('buy_now') }}
                                            </button>
                                        @else
                                            @if ( isset($product->current_stock) && $product->current_stock <= 0)

                                            <span class="string-limit text-danger fw-bold">{{ translate('sorry_product_out_of_stock') }}</span>
                                            @else
                                                <button
                                                class="btn btn-style-black element-center btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }} action-add-to-cart-form"
                                                type="button" data-update-text="{{ translate('update_cart') }}"
                                                data-add-text="{{ translate('add_to_cart') }}">
                                                <span class="string-limit">{{ translate('add_to_cart') }}</span>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-style-black element-center btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }} action-buy-now-this-product">
                                                    <span class="string-limit">{{ translate('buy_now') }}</span>
                                                </button>
                                            @endif
                                        @endif

                                        @if (
                                            ($product->added_by == 'seller' &&
                                                ($sellerTemporaryClose ||
                                                    (isset($product->seller->shop) &&
                                                        $product->seller->shop->vacation_status &&
                                                        $currentDate >= $sellerVacationStartDate &&
                                                        $currentDate <= $sellerVacationEndDate))) ||
                                                ($product->added_by == 'admin' &&
                                                    ($inHouseTemporaryClose ||
                                                        ($inHouseVacationStatus &&
                                                            $currentDate >= $inHouseVacationStartDate &&
                                                            $currentDate <= $inHouseVacationEndDate))))
                                            <div class="alert alert-danger" role="alert">
                                                {{ translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="mt-4 rtl col-12 text-align-direction description-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="wrapper-product-designscription">
                                    <div class="x-contianer">
                                        <div class="row">
                                            <div class="product-speficafication">
                                                <div class="row pt-2 specification">

                                                    @if ($product->video_url != null && str_contains($product->video_url, 'youtube.com/embed/'))
                                                        <div class="col-12 mb-4">
                                                            <iframe width="420" height="315"
                                                                src="{{ $product->video_url }}">
                                                            </iframe>
                                                        </div>
                                                    @endif
                                                    @if ($product['details'])
                                                        <div
                                                            class="text-body col-lg-12 col-md-12 overflow-scroll fs-13 text-justify details-text-justify rich-editor-html-content">
                                                            {!! $product['details'] !!}
                                                        </div>
                                                    @endif

                                                </div>
                                                @if (!$product['details'] && ($product->video_url == null || !str_contains($product->video_url, 'youtube.com/embed/')))
                                                    <div>
                                                        <div class="text-center text-capitalize py-5">
                                                            <img class="mw-90"
                                                                src="{{ theme_asset(path: 'public/assets/front-end/img/icons/nodata.svg') }}"
                                                                alt="">
                                                            <p class="text-capitalize mt-2">
                                                                <small>{{ translate('product_details_not_found') }}
                                                                    !</small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- <div class="col-lg-3">
                            @php($companyReliability = getWebConfig('company_reliability'))
                            @if ($companyReliability != null)
                                <div class="product-details-shipping-details">
                                    @foreach ($companyReliability as $key => $value)
    @if ($value['status'] == 1 && !empty($value['title']))
    <div class="shipping-details-bottom-border">
                                                <div class="px-3 py-3">
                                                    <img class="{{ Session::get('direction') === 'rtl' ? 'float-right ml-2' : 'mr-2' }} __img-20"
                                                         src="{{ getStorageImages(path: imagePathProcessing(imageData: $value['image'], path: 'company-reliability'), type: 'source', source: 'public/assets/front-end/img' . '/' . $value['item'] . '.png') }}"
                                                        alt="">
                                                    <span>{{ translate($value['title']) }}</span>
                                                </div>
                                            </div>
    @endif
    @endforeach
                                </div>
                            @endif

                            @if (getWebConfig(name: 'business_mode') == 'multi')
                            <div class="__inline-31">

                                @if ($product->added_by == 'seller')
                                    @if (isset($product->seller->shop))
                                        <div class="row position-relative">
                                            <div class="col-12 position-relative">
                                                <a href="{{ route('shopView', ['id' => $product->seller->id]) }}" class="d-block">
                                                    <div class="d-flex __seller-author align-items-center">
                                                        <div>
                                                            <img class="__img-60 img-circle" alt=""
                                                                 src="{{ getStorageImages(path: $product?->seller?->shop->image_full_url, type: 'shop') }}">
                                                        </div>
                                                        <div
                                                            class="ms-2 w-0 flex-grow">
                                                            <h6>
                                                                {{ $product->seller->shop->name }}
                                                            </h6>
                                                            <span class="text-capitalize">{{ translate('vendor_info') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">

                                                        @if (
                                                            $sellerTemporaryClose ||
                                                                ($product->seller->shop->vacation_status &&
                                                                    $currentDate >= $sellerVacationStartDate &&
                                                                    $currentDate <= $sellerVacationEndDate))
    <span class="chat-seller-info product-details-seller-info"
                                                                  data-toggle="tooltip"
                                                                  title="{{ translate('this_shop_is_temporary_closed_or_on_vacation') . ' ' . translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}">
                                                                <img src="{{ theme_asset(path: 'public/assets/front-end/img/info.png') }}" alt="i">
                                                            </span>
    @endif
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-6 ">
                                                        <div
                                                            class="d-flex justify-content-center align-items-center rounded __h-79px hr-right-before">
                                                            <div class="text-center">
                                                                <img src="{{ theme_asset(path: 'public/assets/front-end/img/rating.svg') }}"
                                                                     class="mb-2" alt="">
                                                                <div class="__text-12px text-base">
                                                                    <strong>{{ $totalReviews }}</strong> {{ translate('reviews') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div
                                                            class="d-flex justify-content-center align-items-center rounded __h-79px">
                                                            <div class="text-center">
                                                                <img
                                                                    src="{{ theme_asset(path: 'public/assets/front-end/img/products.svg') }}"
                                                                    class="mb-2" alt="">
                                                                <div class="__text-12px text-base">
                                                                    <strong>{{ $productsForReview->count() }}</strong> {{ translate('products') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 position-static mt-3">
                                                <div class="chat_with_seller-buttons">
                                                    @if (auth('customer')->id())
    <button class="btn w-100 d-block text-center web--bg-primary text-white"
                                                                data-toggle="modal"
                                                                data-target="#chatting_modal" {{ $product->seller->shop->temporary_close || ($product->seller->shop->vacation_status && date('Y-m-d') >= date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($product->seller->shop->vacation_end_date))) ? 'disabled' : '' }}>
                                                            <img class="mb-1" alt=""
                                                                src="{{ theme_asset(path: 'public/assets/front-end/img/chat-16-filled-icon.png') }}">
                                                            <span class="d-none d-sm-inline-block text-capitalize">
                                                                {{ translate('chat_with_vendor') }}
                                                            </span>
                                                        </button>
@else
    <a href="{{ route('customer.auth.login') }}"
                                                           class="btn w-100 d-block text-center web--bg-primary text-white">
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/chat-16-filled-icon.png') }}"
                                                                class="mb-1" alt="">
                                                            <span class="d-none d-sm-inline-block text-capitalize">{{ translate('chat_with_vendor') }}</span>
                                                        </a>
    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
@else
    <div class="row d-flex justify-content-between">
                                        <div class="col-9 ">
                                            <a href="{{ route('shopView', [0]) }}" class="row d-flex ">
                                                <div>
                                                    <img class="__inline-32" alt=""
                                                         src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'logo') }}">
                                                </div>
                                                <div class="{{ Session::get('direction') === 'rtl' ? 'right' : 'mt-3 ml-2' }} get-view-by-onclick"
                                                     data-link="{{ route('shopView', [0]) }}">
                                                    <span class="font-bold __text-16px">
                                                        {{ $web_config['name']->value }}
                                                    </span><br>
                                                </div>

                                                @if (
                                                    $product->added_by == 'admin' &&
                                                        ($inHouseTemporaryClose ||
                                                            ($inHouseVacationStatus &&
                                                                $currentDate >= $inHouseVacationStartDate &&
                                                                $currentDate <= $inHouseVacationEndDate)))
    <div class="{{ Session::get('direction') === 'rtl' ? 'right' : 'ml-3' }}">
                                                        <span class="chat-seller-info" data-toggle="tooltip"
                                                              title="{{ translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now') }}">
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/info.png') }}"
                                                                 alt="i">
                                                        </span>
                                                    </div>
    @endif
                                            </a>
                                        </div>

                                        <div class="col-12 mt-2">
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-6 ">
                                                    <div
                                                        class="d-flex justify-content-center align-items-center rounded __h-79px hr-right-before">
                                                        <div class="text-center">
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/rating.svg') }}"
                                                                 class="mb-2" alt="">
                                                            <div class="__text-12px text-base">
                                                                <strong>{{ $totalReviews }}</strong> {{ translate('reviews') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div
                                                        class="d-flex justify-content-center align-items-center rounded __h-79px">
                                                        <div class="text-center">
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/products.svg') }}"
                                                                 class="mb-2" alt="">
                                                            <div class="__text-12px text-base">
                                                                <strong>{{ $productsForReview->count() }}</strong> {{ translate('products') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 position-static mt-3">
                                            <div class="chat_with_seller-buttons">
                                                @if (auth('customer')->id())
    <button class="btn w-100 d-block text-center web--bg-primary text-white"
                                                            data-toggle="modal"
                                                            data-target="#chatting_modal" {{ $inHouseTemporaryClose || ($inHouseVacationStatus && $currentDate >= $inHouseVacationStartDate && $currentDate <= $inHouseVacationEndDate) ? 'disabled' : '' }}>
                                                        <img class="mb-1" alt=""
                                                             src="{{ theme_asset(path: 'public/assets/front-end/img/chat-16-filled-icon.png') }}">
                                                        <span class="d-none d-sm-inline-block text-capitalize">
                                                                {{ translate('chat_with_vendor') }}
                                                            </span>
                                                    </button>
@else
    <a href="{{ route('shopView', [0]) }}" class="text-center d-block w-100">
                                                        <button class="btn text-center d-block w-100 text-white web--bg-primary">
                                                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                            {{ translate('visit_Store') }}
                                                        </button>
                                                    </a>
    @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endif

                            <div class="pt-4 pb-3">
                         git        <span class=" __text-16px font-bold text-capitalize">
                                    @if (getWebConfig(name: 'business_mode') == 'multi')
    {{ translate('more_from_the_store') }}
@else
    {{ translate('you_may_also_like') }}
    @endif
                                </span>
                            </div>
                            <div>

                            </div>
                        </div> -->

            </div>

        </div>
    </div>

    <div class="stickyaddtocart  bg-white d-sm-none">
        <div class="d-flex flex-column gap-1 py-2">
            <div class="d-flex justify-content-center align-items-center fs-13">
                <div class="product-description-label text-dark font-bold">
                    <span class="pdp-bottom-price">{!! getPriceRangeWithDiscount(product: $product) !!}</span>
                </div>
                <!-- &nbsp; <strong id="chosen_price_mobile" class="stickymobilebar"></strong>
                <small class="ml-2  font-regular">
                    (<small>{{ translate('tax') }} : </small>
                    <small id="set-tax-amount-mobile"></small>)
                </small> -->
            </div>
            <div class="d-flex gap-3 justify-content-center">
                @if (
                    ($product->added_by == 'seller' &&
                        ($sellerTemporaryClose ||
                            (isset($product->seller->shop) &&
                                $product->seller->shop->vacation_status &&
                                $currentDate >= $sellerVacationStartDate &&
                                $currentDate <= $sellerVacationEndDate))) ||
                        ($product->added_by == 'admin' &&
                            ($inHouseTemporaryClose ||
                                ($inHouseVacationStatus &&
                                    $currentDate >= $inHouseVacationStartDate &&
                                    $currentDate <= $inHouseVacationEndDate))))
                    <button
                        class="btn btn--primary btn-sm string-limit btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                        type="button" disabled>
                        {{ translate('add_to_cart') }}
                    </button>
                    <button
                        class="btn btn-secondary btn-sm btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                        type="button" disabled>
                        {{ translate('buy_now') }}
                    </button>
                @else
                    <button
                        class="btn btn--primary btn-sm string-limit btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }} action-add-to-cart-form"
                        type="button">
                        <span class="string-limit">{{ translate('add_to_cart') }}</span>
                    </button>
                    <button
                        class="btn btn-secondary btn-sm btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }} action-buy-now-this-product"
                        type="button">
                        <span class="string-limit">{{ translate('buy_now') }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    {{-- {{dd($latest_products)}} --}}
    {{-- @include('web-views.partials._deal-of-the-day', ['decimal_point_settings'=>$decimalPointSettings]) --}}
    @include('web-views.partials._related-products', ['decimal_point_settings'=>$decimalPointSettings])

    <div class="modal fade rtl text-align-direction" id="show-modal-view" tabindex="-1" role="dialog"
        aria-labelledby="show-modal-image" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body flex justify-content-center">
                    <button class="btn btn-default __inline-33 dir-end-minus-7px" data-dismiss="modal">
                        <i class="fa fa-close"></i>
                    </button>
                    <img class="element-center" id="attachment-view" src="" alt="">
                </div>
            </div>
        </div>
    </div>
    <div style="height: 30px;"></div>
    </div>

    @include('layouts.front-end.partials.modal._chatting', [
        'seller' => $product->seller,
        'user_type' => $product->added_by,
    ])


    <span id="route-review-list-product" data-url="{{ route('review-list-product') }}"></span>
    <span id="products-details-page-data" data-id="{{ $product['id'] }}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/product-details.js') }}"></script>
    <script type="text/javascript" async="async"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons">
    </script>
     <script>
        window.onload = function() {
            const widgetConfig = {
                "key": "bYPCMm",
                "amount": {{ $product->unit_price }},
            };
            payuAffordability.init(widgetConfig);
        }
    </script>
@endpush
