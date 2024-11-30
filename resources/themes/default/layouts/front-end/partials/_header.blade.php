@if (request()->is('/'))

    <style>
        .marquee-text {
            box-sizing: border-box;
            -webkit-box-align: center;
            -moz-box-align: center;
            -o-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
            overflow: hidden;
            background: #000;
            /* min-height: 70px;
            max-height: 70px; */
        }

        .marquee-text .top-info-bar {
            font-size: 8px;
            width: 200%;
            display: flex;
            -webkit-animation: marquee 14s linear infinite running;
            -moz-animation: marquee 14s linear infinite running;
            -o-animation: marquee 14s linear infinite running;
            -ms-animation: marquee 14s linear infinite running;
            animation: marquee 14s linear infinite running;
        }

        /* .marquee-text .top-info-bar:hover {
            -webkit-animation-play-state: paused;
            -moz-animation-play-state: paused;
            -o-animation-play-state: paused;
            -ms-animation-play-state: paused;
            animation-play-state: paused;
        } */

        .marquee-text .top-info-bar .info-text {
            padding: 10px 30px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            -webkit-transition: all .1s ease;
            transition: all .1s ease;
        }

        .marquee-text .top-info-bar a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            text-transform: uppercase;
            font-family: "Space Grotesk", sans-serif;
            font-weight: 500;
            line-height: 25px;
        }

        @-moz-keyframes marquee {
            0% {
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
                -o-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
            }

            100% {
                -webkit-transform: translate(-50%);
                -moz-transform: translate(-50%);
                -o-transform: translate(-50%);
                -ms-transform: translate(-50%);
                transform: translate(-50%);
            }
        }

        @-webkit-keyframes marquee {
            0% {
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
                -o-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
            }

            100% {
                -webkit-transform: translate(-50%);
                -moz-transform: translate(-50%);
                -o-transform: translate(-50%);
                -ms-transform: translate(-50%);
                transform: translate(-50%);
            }
        }

        @-o-keyframes marquee {
            0% {
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
                -o-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
            }

            100% {
                -webkit-transform: translate(-50%);
                -moz-transform: translate(-50%);
                -o-transform: translate(-50%);
                -ms-transform: translate(-50%);
                transform: translate(-50%);
            }
        }

        @keyframes marquee {
            0% {
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
                -o-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
            }

            100% {
                -webkit-transform: translate(-50%);
                -moz-transform: translate(-50%);
                -o-transform: translate(-50%);
                -ms-transform: translate(-50%);
                transform: translate(-50%);
            }
        }
    </style>
    @if (isset($announcement) && $announcement['status'] == 1)
        <div class="marquee-text"
            style="background-color: {{ $announcement['color'] }};color:{{ $announcement['text_color'] }}">
            <div class="top-info-bar">
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
                <div class="fl-1 info-text">
                    <a href="javascript:void(0);">{{ $announcement['announcement'] }} </a>
                </div>
            </div>
        </div>
    @endif
@endif
<header class="rtl __inline-10">
    <div class="navbar-sticky mobile-head" style="background-color: #fcfcfc">
        <div class="navbar navbar-expand-md navbar-light header-wrapper">
            <div class="container-fluid header-container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand d-none d-sm-block mr-3 flex-shrink-0 __min-w-7rem" href="{{ route('home') }}">
                    <img class="__inline-11" src="{{ getStorageImages(path: $web_config['web_logo'], type: 'logo') }}"
                        alt="{{ $web_config['name']->value }}" style="height: 68px !important">
                </a>
                <a class="navbar-brand d-sm-none" href="{{ route('home') }}">
                    <img class="mobile-logo-img __inline-12"
                        src="{{ getStorageImages(path: $web_config['mob_logo'], type: 'logo') }}"
                        alt="{{ $web_config['name']->value }}" style="" />
                </a>

                <div class="dropdown d-md-none">
                    <a class="navbar-tool ml-3" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="navbar-tool-icon-box">
                            <div class="navbar-tool-icon-box ">
                                <img class="accountlogin-image" alt="search icon"
                                    src="{{ asset('myfigma/search_icon.png') }}">
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                        aria-labelledby="dropdownMenuButton">
                        <div class="input-group-overlay px-2  text-align-direction">
                            <form action="{{ route('products') }}" type="submit" class="search_form">
                                <div class="d-flex align-items-center gap-2">
                                    <input class="form-control appended-form-control search-bar-input" type="search"
                                        autocomplete="off" data-given-value=""
                                        placeholder="{{ translate('search_for_items') }}..." name="name"
                                        value="{{ request('name') }}">
                                </div>

                                <input name="data_from" value="search" hidden>
                                <input name="page" value="1" hidden>
                                <diV class="card search-card mobile-search-card">
                                    <div class="card-body">
                                        <div class="search-result-box __h-400px overflow-x-hidden overflow-y-auto">
                                        </div>
                                    </div>
                                </diV>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div id="cart_item" class="d-md-none">
                        @include('layouts.front-end.partials._cart')
                    </div>
                </div>

            </div>
        </div>
        <div class="navbar navbar-expand-md headermenus">
            <div class="container-fluid">
                <div class="d-flex justify-content-space-between align-items-center wrapperprimary-header w-100">
                    <div class="collapse navbar-collapse text-align-direction" id="navbarCollapse">
                        <div class="w-100 d-md-none text-align-direction">
                            <button class="navbar-toggler p-0" type="button" data-toggle="collapse"
                                data-target="#navbarCollapse">
                                <i class="tio-clear __text-26px"></i>
                            </button>
                        </div>

                        <ul class="navbar-nav d-block d-md-none">
                            <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('home') }}">{{ translate('home') }}</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav menuitems">
                            <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('home') }}">{{ translate('home') }}</a>
                            </li>

                            @php($brandIndex = 0)
                            @foreach (\App\Models\Brand::orderBy('sequence','desc')->take(10)->get() as $brand)
                                @php($brandIndex++)
                                @if ($brandIndex < 10)
                                    <li
                                        class="nav-item dropdown d-md-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                                        <a class="nav-link"
                                            href="{{ route('products', ['id' => $brand['id'], 'data_from' => 'brand', 'page' => 1]) }}">{{ $brand['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if (auth('customer')->check())
                                <li class="nav-item d-md-none">
                                    <a href="{{ route('user-account') }}" class="nav-link text-capitalize">
                                        {{ translate('user_profile') }}
                                    </a>
                                </li>
                                <li class="nav-item d-md-none">
                                    <a href="{{ route('wishlists') }}" class="nav-link">
                                        {{ translate('Wishlist') }}
                                    </a>
                                </li>
                            @else
                                <li class="nav-item d-md-none">
                                    <a class="dropdown-item pl-2" href="{{ route('customer.auth.login') }}">
                                        <i class="fa fa-sign-in mr-2"></i> {{ translate('sign_in') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                </li>
                                {{-- <li class="nav-item d-md-none">
                                    <a class="dropdown-item pl-2" href="{{ route('customer.auth.sign-up') }}">
                                        <i class="fa fa-user-circle mr-2"></i>{{ translate('sign_up') }}
                                    </a>
                                </li> --}}
                            @endif
                        </ul>

                        @if (auth('customer')->check())
                            <div class="logout-btn mt-auto d-md-none">
                                <hr>
                                <a href="{{ route('customer.auth.logout') }}" class="nav-link">
                                    <strong class="text-base">{{ translate('logout') }}</strong>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="navbar-toolbar d-none d-md-flex flex-shrink-0 align-items-center">
                        <div class="dropdown">
                            <a class="navbar-tool ml-3" type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="navbar-tool-icon-box bg-secondary">
                                    <img class="accountlogin-image" alt="search icon"
                                        src="{{ asset('myfigma/search_icon.png') }}">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                aria-labelledby="dropdownMenuButton">
                                <div class="input-group-overlay px-2 text-align-direction">
                                    <form action="{{ route('products') }}" type="submit" class="search_form">
                                        <div class="d-flex align-items-center gap-2">
                                            <input class="form-control appended-form-control search-bar-input-mobile"
                                                type="search" autocomplete="off" data-given-value=""
                                                placeholder="{{ translate('search_for_items') }}..." name="name"
                                                value="{{ request('name') }}">
                                        </div>
                                        <input name="data_from" value="search" hidden>
                                        <input name="page" value="1" hidden>
                                        <div class="card search-card mobile-search-card">
                                            <div class="card-body">
                                                <div
                                                    class="search-result-box __h-400px overflow-x-hidden overflow-y-auto">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if (auth('customer')->check())
                            <div class="dropdown">
                                <a class="navbar-tool ml-3" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <div class="navbar-tool-icon-box bg-secondary">
                                        <img class="accountlogin-image" alt="search icon"
                                            src="{{ getStorageImages(path: auth('customer')->user()->image_full_url, type: 'avatar') }}">
                                    </div>
                                    <div class="navbar-tool-text">
                                        <small>{{ translate('hello') }},
                                            {{ auth('customer')->user()->f_name }}</small>
                                        {{ translate('dashboard') }}
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                    aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('account-oder') }}">
                                        {{ translate('my_Order') }} </a>
                                    <a class="dropdown-item" href="{{ route('user-account') }}">
                                        {{ translate('my_Profile') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                        href="{{ route('customer.auth.logout') }}">{{ translate('logout') }}</a>
                                </div>
                            </div>
                        @else
                            <div class="dropdown">
                                <a class="navbar-tool {{ Session::get('direction') === 'rtl' ? 'mr-md-3' : 'ml-md-3' }}"
                                    type="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <div class="navbar-tool-icon-box bg-secondary">
                                        <img src="{{ asset('myfigma/account_login_icon.png') }}"
                                            alt="account login person" class="accountlogin-image">
                                    </div>
                                </a>
                                <div class="text-align-direction login-dropdown dropdown-menu __auth-dropdown mt-2 dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                    aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('customer.auth.login') }}">
                                        <i class="fa fa-sign-in mr-2 d-none"></i> {{ translate('sign_in') }}
                                    </a>
                                    {{-- <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('customer.auth.sign-up') }}">
                                        <i class="fa fa-user-circle mr-2 d-none"></i>{{ translate('sign_up') }}
                                    </a> --}}
                                </div>
                            </div>
                        @endif

                        <div id="cart_items">
                            @include('layouts.front-end.partials._cart')
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</header>

@push('script')
    <script>
        "use strict";

        $(".category-menu").find(".mega_menu").parents("li")
            .addClass("has-sub-item").find("> a")
            .append("<i class='czi-arrow-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}'></i>");
    </script>
@endpush
