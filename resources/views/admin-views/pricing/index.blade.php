@extends('layouts.back-end.app')

@section('title', translate('pricing'))
@push('css_or_js')
    <style>
        ul,
        li {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .center-heading {
            text-align: center;
        }

        .center-heading .section-title {
            font-weight: 400;
            font-size: 28px;
            color: #3B566E;
            letter-spacing: 1.75px;
            line-height: 38px;
            margin-bottom: 20px;
        }

        .center-heading.colored .section-title {
            color: #ffffff;
        }

        .center-text {
            text-align: center;
            font-weight: 400;
            font-size: 16px;
            color: #6F8BA4;
            line-height: 28px;
            letter-spacing: 1px;
            margin-bottom: 50px;
        }

        .center-text.colored {
            color: #FFC0EB;
        }

        .center-text p {
            margin-bottom: 30px;
        }

        .pricing-item {
            background: #FFFFFF;
            box-shadow: 0 2px 48px 0 rgba(0, 0, 0, 0.13);
            border-radius: 5px;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .pricing-item.active .pricing-header {
            position: relative;
        }

        .pricing-item.active .pricing-header .pricing-title {
            color: #fff;
        }

        .pricing-item.active .pricing-body .price-wrapper {
            background-image: linear-gradient(135deg, #e73d3d 0%, #decb3d 100%);
        }

        .pricing-item.active .pricing-body .price-wrapper .currency {
            color: #fff;
        }

        .pricing-item.active .pricing-body .price-wrapper .price {
            color: #fff;
        }

        .pricing-item.active .pricing-body .price-wrapper .period {
            color: #fff;
        }

        .pricing-item .pricing-header {
            text-align: center;
            display: block;
            position: relative;
            padding-bottom: 10px;
        }

        .pricing-item .pricing-header .pricing-title {
            font-weight: 400;
            font-size: 14px;
            letter-spacing: 1px;
            color: #fff;
            position: absolute;
            width: 180px;
            height: 40px;
            line-height: 40px;
            left: 0px;
            right: 0px;
            margin: auto;
            top: -20px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            background-image: linear-gradient(135deg, #e73d3d 0%, #decb3d 100%);
        }

        .pricing-item .pricing-body {
            margin-bottom: 40px;
        }

        .pricing-item .pricing-body .price-wrapper {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 30px;
            padding-top: 10px;
            background: #f6f6f6;
        }

        .pricing-item .pricing-body .price-wrapper .currency {
            height: 47px;
            font-weight: 600;
            font-size: 20px;
            color: #e64b3d;
            position: relative;
            top: -15px;
        }

        .pricing-item .pricing-body .price-wrapper .price {
            font-weight: 700;
            font-size: 34px;
            color: #e64b3d;
            letter-spacing: 2.12px;
        }

        .pricing-item .pricing-body .price-wrapper .period {
            font-weight: 700;
            font-size: 14px;
            color: #e64b3d;
            letter-spacing: 0.88px;
        }

        .pricing-item .pricing-body .list li {
            text-align: center;
            margin-bottom: 12px;
            font-weight: 400;
            font-size: 14px;
            color: #CCDCEA;
            letter-spacing: 0.88px;
            text-decoration: line-through;
        }

        .pricing-item .pricing-body .list li.active {
            color: #3B566E;
            text-decoration: none;
        }

        .pricing-item .pricing-footer {
            text-align: center;
        }

        .btn-primary-line {
            width: 160px;
            margin: auto;
            display: inline-block;
            height: 44px;
            line-height: 45px;
            text-align: center;
            border: none;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            border: 1px solid #fff;
            margin-bottom: 40px;
            font-weight: 700;
            font-size: 12px;
            color: #fff;
            letter-spacing: 0.75px;
            text-transform: uppercase;
            -webkit-transition: all 0.3s ease 0s;
            -moz-transition: all 0.3s ease 0s;
            -o-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
            outline: none !important;
            cursor: pointer;
            text-decoration: none !important;
            position: relative;
        }

        .pricing-item .pricing-footer .btn-primary-line {
            border: 1px solid #e64b3d;
            color: #e64b3d;
            height: 36px;
            line-height: 36px;
        }

        .pricing-item .pricing-footer .btn-primary-line:hover {
            background: #e64b3d;
            color: #fff;
        }
    </style>
@endpush
@section('content')
    <div class="content container-fluid">

        <section class="section pt-5 pb-5" id="pricing-plans">
            <div class="top"></div>
            <div class="container">
                <!-- ***** Section Title Start ***** -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="center-heading">
                            <h2 class="section-title">Dashboard Charges</h2>
                        </div>
                    </div>
                </div>
                <!-- ***** Section Title End ***** -->

                <div class="row mt-4">
                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Website Dashboard </h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">2,25,000</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Website Dashboard & Backend Tech </li>
                                    <li class="active">One Time Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://razorpay.com/payment-link/plink_P6ttCu1KgQmuDF" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Home Page Setup </h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">25,000</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Home Page, PDP Page, Checkpot Retainer</li>
                                    <li class="active">Monthly Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://api.razorpay.com/v1/l/subscriptions/sub_P6teEcGZRfcsye" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Server Maintenance</h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">40,000</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Monthly Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://api.razorpay.com/v1/l/subscriptions/sub_P5JyJ5WU96GuOW" target="_blank" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Google Listing </h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">35,000</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Google Listing Management & Analysis with GTIN Tracker</li>
                                    <li class="active">Monthly Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://api.razorpay.com/v1/l/subscriptions/sub_P6tkGDICh3rxlL" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Automation Wigzoo</h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">50,000</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Monthly Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://api.razorpay.com/v1/l/subscriptions/sub_P6tm6SoqJjJuRF" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Whatsapp Integration</h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">39,633</span>
                                </div>
                                <ul class="list">
                                    <li class="active">One Time Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://api.razorpay.com/v1/l/subscriptions/sub_P6tpWTP0g6fhyX" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                    <!-- ***** Pricing Item Start ***** -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="pricing-item">
                            <div class="pricing-header">
                                <h3 class="pricing-title">Cloud Enterprise Hosting</h3>
                            </div>
                            <div class="pricing-body">
                                <div class="price-wrapper">
                                    <span class="currency">₹</span>
                                    <span class="price">34,566</span>
                                </div>
                                <ul class="list">
                                    <li class="active">Yearly Cost + GST</li>
                                </ul>
                            </div>
                            <div class="pricing-footer">
                                <a href="https://razorpay.com/payment-link/plink_P6tvfU9vMVZdc4" class="btn-primary-line">Pay Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- ***** Pricing Item End ***** -->

                </div>
            </div>
        </section>
    </div>
@endsection
