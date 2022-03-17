<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/paytm-payment/status',
        '/jazzcash-payment-status',
        '/payumoney-payment-success',
        '/payumoney-payment-failed',
        'install', 'install/*','search',

//        Start SslComerze
          '/ssl-commerz/success','/ssl-commerz/cancel','/ssl-commerz/fail','/ssl-commerz/ipn'
//        End
    ];
}
