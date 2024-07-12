<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WhitelistIpMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Define your list of whitelisted IP addresses
        $whitelistedIps = [
            '10.9.116.180',
            '192.168.1.34',
            '127.0.0.1'
        ];

        // Get the client's IP address
        $clientIp = $request->ip();
        // dd($clientIp, $whitelistedIps);
        // Check if the client's IP is in the whitelist
        if (in_array($clientIp, $whitelistedIps)) {

            // dd('pass thorlting logic');
            return $next($request);
        }

        // Continue with normal throttling logic for other requests
        return $next($request);
    }
}
