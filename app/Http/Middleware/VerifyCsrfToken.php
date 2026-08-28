<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'phonepe-confirm',
        '/razorpay/payment/success', // Exclude this Razorpay callback route
        '/payment/success', // Add other Razorpay callback URLs if needed
        '/handle-payment-update',
        '/popup-track',
    ];
}
