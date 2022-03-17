@php
    $MERCHANT_KEY = env('PAYU_MONEY_KEY');
    $SALT = env('PAYU_MONEY_SALT');
    // Merchant Key and Salt as provided by Payu.

    if (env('PAYU_MONEY_MODE') == "TEST_MODE") {
    $PAYU_BASE_URL = "https://test.payu.in/_payment";
    }
    else {
    $PAYU_BASE_URL = "https://secure.payu.in/_payment";
    }
    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    $posted =  array(
    'key' => $MERCHANT_KEY,
    'txnid' => $txnid,
    'amount' => $total_amount,
    'firstname' => $address->name,
    'email' => $address->email,
    'phone' => null,
    'productinfo' => 'walletRecharge',
    'surl' => route('payumoney.success'),
    'furl' => route('payumoney.failed'),
    'service_provider' => 'payu_paisa',
    );

    $hash = '';
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

    if(empty($posted['hash']) && sizeof($posted) > 0) {
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
        $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
        $hash_string .= '|';
    }
    $hash_string .= $SALT;

    $hash = strtolower(hash('sha512', $hash_string));
    }
@endphp

    <div class="col-lg-12">
    <form id="contactForm" enctype="multipart/form-data" action="{{$PAYU_BASE_URL}}" class="p-0" method="POST">
    @csrf
    <input type="hidden" name="method" value="PayUMoney">
    <input type="hidden" name="amount" value="{{ $total_amount}}">

    <input type="hidden" name="key" value="{{ $MERCHANT_KEY }}"/>
    <input type="hidden" name="txnid" value="{{ $txnid }}"/>
    <input type="hidden" name="surl" value="{{ route('payumoney.success') }}"/>
    <input type="hidden" name="furl" value="{{ route('payumoney.success') }}"/>
    <input type="hidden" name="hash" value="{{ $hash }}"/>
    <input type="hidden" name="service_provider" value="payu_paisa"/>
    <input type="hidden" name="productinfo" value="Checkout"/>

    <div class="row">
        <div class="col-lg-12">
            <label for="">{{ __('common.name') }} <span class="text-danger">*</span></label>
            <input class="form-control" type="text" required name="name" placeholder="{{ __('common.name') }}" value="{{$address->name}}">
        </div>
        <div class="col-lg-12">
            <label for="">{{ __('common.email') }}<span class="text-danger">*</span></label>
            <input class="form-control" type="text" required name="email" placeholder="{{ __('common.email') }}" value="{{$address->email}}">
        </div>
        <div class="col-lg-12">
            <label for="">{{ __('common.mobile') }}<span class="text-danger">*</span></label>
            <input class="form-control" type="text" required name="mobile" placeholder="{{ __('common.mobile') }}" value="{{@old('mobile')}}">
        </div>
    </div>
    <button class="btn_1 d-none" id="payumoney_btn" type="submit">{{ __('wallet.continue_to_pay') }}</button>
    </form>
    </div>