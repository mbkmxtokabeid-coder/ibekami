<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * '*' = trust semua proxy — diperlukan untuk shared hosting (Hostinger, Niagahoster, dll)
     * karena request masuk melalui reverse proxy/load balancer sebelum sampai ke PHP.
     *
     * Tanpa ini: Laravel tidak tahu request aslinya HTTPS, sehingga generate URL http://
     * dan terjadi redirect loop (ERR_TOO_MANY_REDIRECTS).
     *
     * @var string|array<int, string>|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * HEADER_X_FORWARDED_FOR      : IP asli client
     * HEADER_X_FORWARDED_HOST     : Host asli (domain)
     * HEADER_X_FORWARDED_PORT     : Port asli
     * HEADER_X_FORWARDED_PROTO    : Protocol asli (http/https) ← KUNCI anti-redirect-loop
     * HEADER_X_FORWARDED_PREFIX   : Path prefix
     * HEADER_X_FORWARDED_AWS_ELB  : AWS Elastic Load Balancer
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_PREFIX |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
