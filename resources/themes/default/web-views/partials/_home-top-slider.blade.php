@if (count($main_banner) > 0)
    <section class="bg-transparent managemobileview">
        <div class="container-fluid position-relative">
            <div class="row-outer hero-slider-wrapper">

                <div class="d-flex slider-area">
                    <div class="sliderimages">
                        <div class="owl-theme owl-carousel hero-slider">
                            @foreach ($main_banner as $key => $banner)
                                <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                                    <img class="__slide-img" alt=""
                                        src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}">
                                </a>
                            @endforeach
                        </div>
                    </div>


                    {{-- <!--@if ($categories->count() > 0)-->
                    <div class="categoryblock">
                        <div class="d-flex sliderstaticimages">
                            @forelse ($main_banner_right as $banner)
                                <div class="upperimage">
                                    <img src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}"
                                        alt="Main banner left" />
                                </div>
                            @empty
                                <div class="upperimage">
                                    <img src="{{ asset('myfigma/vivoupperimage.png') }}" alt="product image 01" />
                                </div>
                                <div class="upperimage">
                                    <img src="{{ asset('/myfigma/airpodslowerimage.png') }}" alt="product image 02" />
                                </div>
                            @endforelse

                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </section>
@endif
