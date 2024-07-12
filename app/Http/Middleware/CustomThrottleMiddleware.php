<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleMiddleware
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decaySeconds
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decaySeconds = 1)
    {
        // List of IP addresses that are whitelisted
        $whitelist = [
            '10.9.116.180',
            '192.168.1.34'
        ];

        // Check if the requester's IP is in the whitelist
        if (in_array($request->ip(), $whitelist)) {
            return $next($request);
        }

        // Apply rate limiting for non-whitelisted IPs
        return $this->limiter->hit(
            $this->getRateLimitKey($request),
            $maxAttempts,
            $decaySeconds,
            function () use ($request, $next) {
                return $next($request);
            }
        );
    }

    /**
     * Get the rate limit key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getRateLimitKey($request)
    {
        return sha1($request->path() . '|' . $request->ip());
    }
}
