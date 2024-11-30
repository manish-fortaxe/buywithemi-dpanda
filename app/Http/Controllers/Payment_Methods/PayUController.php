<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Models\PaymentRequest;
use App\Models\User;
use App\Traits\Processor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Facades\Payu;

class PayUController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private $user;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('payu', 'payment_config');
        $razor = false;
        if (!is_null($config) && $config->mode == 'live') {
            $razor = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $razor = json_decode($config->test_values);
        }

        if ($razor) {
            $config = array(
                'api_key' => $razor->api_key,
                'api_secret' => $razor->api_secret
            );
            Config::set('payu_config', $config);
        }

        $this->payment = $payment;
        $this->user = $user;
    }

    public function index(Request $request): View|Factory|JsonResponse|Application
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        Session::put('payment_id', $request['payment_id']);
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
        $payer = json_decode($data['payer_information']);

        if ($data['additional_data'] != null) {
            $business = json_decode($data['additional_data']);
            $business_name = $business->business_name ?? "buywithemi";
            $business_logo = $business->business_logo ?? url('/');
        } else {
            $business_name = "buywithemi";
            $business_logo = url('/');
        }
        $cust_user = User::find($data->payer_id);

        $customer = Customer::make()
            ->firstName($cust_user->f_name)
            ->email($cust_user->email);
        $transaction = Transaction::make()
            ->charge(round($data->payment_amount, 2))
            ->for('Product')
            ->to($customer);
        $params = [
            'txnid' => $transaction->transactionId,
            'amount' => $data->payment_amount,
            'productinfo' => 'Product',
            'firstname' => $cust_user->f_name,
            'lastname' => $cust_user->l_name,
            'zipcode' => $cust_user->zip,
            'email' => $cust_user->email,
            'phone' => $cust_user->phone,
            'address1' => $cust_user->street_address,
            'city' => $cust_user->city,
            'country' => $cust_user->country,
            'udf1' => '', // User-defined field 1
            'udf2' => '', // User-defined field 2
            'udf3' => '', // User-defined field 3
            'udf4' => '', // User-defined field 4
            'udf5' => '', // User-defined field 5
        ];
        $pg = Null;
        $bank_codes = Null;
        $enforce_paymethod = Null;
        if ($data->payment_method_type == 'card') {
            // Enforce credit and debit cards
            $enforce_paymethod = 'creditcard|debitcard';
        } elseif ($data->payment_method_type == 'upi') {
            // Enforce UPI
            $enforce_paymethod = 'upi';
        } elseif ($data->payment_method_type == 'netbanking') {
            // Enforce netbanking
            $enforce_paymethod = 'netbanking';
        } elseif ($data->payment_method_type == 'wallet') {
            // Enforce wallet
            $enforce_paymethod = 'cashcard';
        } elseif ($data->payment_method_type == 'emi') {
            // Enforce emi
            $enforce_paymethod = 'EMI|EMI6|EMI9|EMI12|EMI18|EMI24|EMI36|EMI30|EMI48|EMIK3|EMIK6|EMIK9|EMIK12|EMIK18|EMIK24|EMIK36|EMIAMEX3|EMIAMEX6|EMIAMEX9|EMAMEX12|SBI03|SBI06|SBI09|SBI12|SBI18|SBI24|EMIIC3|EMIIC6|EMIIC9|EMIIC12|EMIIC18|EMIIC24|EMIIND3|EMIIND6|EMIIND9|EMIIND12|EMIIND18|EMIIND24|EMIIND36|EMIHS03|EMIHS06|EMIHS09|EMIHS12|EMIHS18|EMIHS24|EMISCB3|EMISCB6|EMISCB9|EMISCB12|EMISCB18|EMISCB24|EMIA3|EMIA6|EMIA9|EMIA12|EMIA18|EMIA24|EMIRBL3|EMIRBL6|EMIRBL9|EMIRBL12|EMIRBL18|EMIRBL24|EMIY03|EMIY06|EMIY09|EMIY12|EMIY18|EMIY24|DBS03|DBS06|DBS09|DBS12|DBS18|DBS24|IDBI03|IDBI06|IDBI09|IDBI12|IDBI18|IDBI24|IDBI30|IDBI36|FDRL03|FDRL06|FDRL09|FDRL12|FDRL18|FDRL24|IDFC03|IDFC06|IDFC09|IDFC12|IDFC15|IDFC24|IDFC36|IDFC18|CANARA03|CANARA06|CANARA09|CANARA12|CANARA18|CANARA24|AUSF03|AUSF06|AUSF09|AUSF12|AUSF18|AUSF24|BOBCC02|BOBCC03|BOBCC04|BOBCC05|BOBCC06|BOBCC07|BOBCC08|BOBCC09|BOBCC12|BOBCC18|BOBCC24|BOBCC36|EMI03|EMI06|EMI09|EMI012|EMI018|EMI024';

        } elseif($data->payment_method_type == 'bajaj_emi') {
            // Enforce bajaj_emi
            $enforce_paymethod = 'BAJFIN02|BAJFIN03|BAJFIN06|BAJFIN09|BAJFIN12';
        } elseif($data->payment_method_type == 'zest_money') {
            // Enforce zestmoney
            $enforce_paymethod = 'ZESTMON';
        } elseif($data->payment_method_type == 'kotak_bank') {
            // Enforce kotak
            $enforce_paymethod = 'KOTAKD01|KOTAKD02|KOTAKD03|KOTAKD06|KOTAKD09|KOTAKD12';
        } elseif($data->payment_method_type == 'hdfc') {
            // Enforce hdfc
            $enforce_paymethod = 'HDFCD03|HDFCD06|HDFCD09|HDFCD12|HDFCD18|HDFCD24|HDFCD36|HDFCD48';
        } elseif($data->payment_method_type == 'icici') {
            // Enforce icici
            $enforce_paymethod = 'ICICID03|ICICID06|ICICID09|ICICID12';
        } elseif($data->payment_method_type == 'one_card') {
            // Enforce one card
            $enforce_paymethod = 'ONEC03|ONEC06|ONEC09|ONEC12|ONEC18|ONEC24';
        } elseif($data->payment_method_type == 'home_credit') {
            // Enforce home_credit card
            $enforce_paymethod = 'HMECDT03|HMECDT06';
        } else {
            // Enforce emi
            $pg = 'EMI';
            $bank_codes = 'EMI';
        }
        Log::info('payload',[$enforce_paymethod]);
        $key = config('payu_config.api_key');
        $salt = config('payu_config.api_secret');
        $url = 'https://secure.payu.in/_payment';
        $success_url = route('payu.payment');
        $failure_url = route('payu.payment');
        $hash_string = $key . '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|' . $params['udf1'] . '|' . $params['udf2'] . '|' . $params['udf3'] . '|' . $params['udf4'] . '|' . $params['udf5'] . '||||||' . $salt;
        $hash = hash('sha512', $hash_string);

        return view('payment.payu', compact('data', 'payer', 'business_logo', 'business_name', 'url', 'key', 'success_url', 'failure_url', 'params', 'hash','enforce_paymethod','pg','bank_codes'));
    }


    public function payment(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $input = $request->all();
        $payment_id = Session::get('payment_id');

        if (count($input) && !empty($input['mihpayid']) && isset($payment_id) && $input['status'] == 'success' ) {

            $this->payment::where(['id' => $payment_id])->update([
                'payment_method' => 'payu',
                'is_paid' => 1,
                'transaction_id' => $input['mihpayid'],
            ]);
            $data = $this->payment::where(['id' => $payment_id])->first();
            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }
            return $this->payment_response($data, 'success');
        }
        $payment_data = $this->payment::where(['id' => $payment_id])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }
}
