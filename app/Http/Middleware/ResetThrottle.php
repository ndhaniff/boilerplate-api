<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ResetThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ($response->getStatusCode() != 200 && $response->getStatusCode() != 429) {
            $keyPrefix = sha1($request->path());
            $requestKey = sprintf('dingo.api.%s.%s.%s', $keyPrefix, 'requests', $request->getClientIp());
            $expiresKey = sprintf('dingo.api.%s.%s.%s', $keyPrefix, 'expires', $request->getClientIp());
            $resetKey = sprintf('dingo.api.%s.%s.%s', $keyPrefix, 'reset', $request->getClientIp());
            Cache::forget($requestKey);
            Cache::forget($expiresKey);
            Cache::forget($resetKey);
        }
    }
}