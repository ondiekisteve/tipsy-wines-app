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
        "api/v1/generateAccessToken",
        "api/v1/customerMpesaSTKPush",
        "api/v1/validationCallback",
        "api/v1/confirmationCallback",
        "api/v1/stkConfirmationCallback",
        "api/v1/registerUrls",
    ];
}
