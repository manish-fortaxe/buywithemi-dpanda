@extends('payment.layouts.master')

@section('content')
    <div>
        <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>
    </div>

    <form action="{!!route('razor-pay.payment',['payment_id'=>$data->id])!!}" id="form" method="POST">
    @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <button id="rzp-button1" class="btn btn-outline-dark btn-lg" type="button">
            <i class="fas fa-money-bill"></i> Pay with IDFC First Bank
        </button>

        <script>
            var options = {
                "key": "{{ config()->get('razor_config.api_key') }}", // Your Razorpay Key ID
                "amount": "{{ round($data->payment_amount, 2) * 100 }}", // Amount in paise
                "currency": "{{ $data->currency_code }}",
                "description": "Payment for {{ $business_name }}",
                "image": "{{ $business_logo }}",
                "prefill": {
                    "name": "{{ $payer->name ?? '' }}",
                    "email": "{{ $payer->email ?? '' }}",
                    "contact": "{{ $payer->phone ?? '' }}",
                },
                "config": {
                    display: {
                        blocks: {
                            idfc: {
                                name: "Pay Using IDFC First Bank",
                                instruments: [
                                    {
                                        method: "cardless_emi",
                                        providers: ["IDFB"] // Only allow IDFC First Bank cards (issuer: IDFB)
                                    }
                                ]
                            }
                        },
                        hide: [
                            {
                                method: "netbanking"  // Hide netbanking option
                            },
                            {
                                method: "upi"         // Hide UPI option
                            },
                            {
                                method: "wallet"      // Hide wallet option
                            },
                            {
                                method: "emi"         // Hide EMI option
                            },
                            {
                                method: "paylater"    // Hide Pay Later option
                            }
                        ],
                        sequence: ["block.idfc"],
                        preferences: {
                            show_default_blocks: false // Disable default blocks
                        }
                    }
                },
                "handler": function (response) {
                    alert("Payment Successful! Razorpay Payment ID: " + response.razorpay_payment_id);
                },
                "modal": {
                    "ondismiss": function () {
                        if (confirm("Are you sure you want to close the form?")) {
                            console.log("Checkout form closed by the user");
                        } else {
                            console.log("Complete the Payment");
                        }
                    }
                }
            };

            var rzp1 = new Razorpay(options);

            // Automatically click the pay button when the page loads
            document.addEventListener("DOMContentLoaded", function () {
                rzp1.open(); // Open Razorpay checkout automatically
            });
        </script>
    </form>
@endsection
