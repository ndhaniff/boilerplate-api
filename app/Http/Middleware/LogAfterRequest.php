<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Carbon\Carbon;
use Closure;
use JWTAuth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Collection;

class LogAfterRequest
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        try {
            if ($request->path() != 'web/artisan-scheduler') {
                $request->offsetUnset('_method');
                $request->offsetUnset('password');
                $request->offsetUnset('password_confirmation');
                $deviceInfo = $request->headers->get('device_info');
                $companyInfo = $request->headers->get('company_id');
                $versionInfo = $request->headers->get('Accept');
                $timezoneInfo = $request->headers->get('Time-Zone');
                $responseTime = round(microtime(true) - LARAVEL_START, 2);

                if ($companyInfo == 'null') {
                    $companyInfo = null;
                }

                if (!is_string($deviceInfo)) {
                    $deviceInfo = json_encode($deviceInfo);
                }

                $currentUser = null;
                if ($request->header('Authorization')) {
                    try {
                        $currentUser = JWTAuth::parseToken()->authenticate();
                        $currentUser = $currentUser->id;
                    } catch (\Exception $e) {
                        if ($request->path() == 'v1/logout') {
                            $currentUser = json_decode($response->content(), true)['id'];
                        }
                    }
                }

                if ($response instanceof StreamedResponse) {
                    if ($response->getStatusCode() === 500) {
                        RequestLog::create([
                            'user_id' => $currentUser ?? null,
                            'company_id' => $companyInfo ?? null,
                            'status' => $response->getStatusCode(),
                            'ip' => request()->ip(),
                            'path' => 'get request file',
                            'request' => substr(json_encode($request->input()), 0, 4000),
                            'response' => substr($response->content(), 0, 4000),
                            'remark' => $deviceInfo,
                            'timezone' => $timezoneInfo,
                            'version' => substr($versionInfo, 0, 100),
                            'response_time' => $responseTime,
                        ]);
                    }
                } else {
                    RequestLog::create([
                        'user_id' => $currentUser ?? null,
                        'company_id' => $companyInfo ?? null,
                        'method' => $request->method(),
                        'status' => $response->status(),
                        'ip' => request()->ip(),
                        'path' => $request->path(),
                        'request' => substr(json_encode($request->input()), 0, 4000),
                        'response' => $response->status() != 200 ? substr($response->content(), 0, 4000) : null,
                        'remark' => $deviceInfo,
                        'timezone' => $timezoneInfo,
                        'version' => substr($versionInfo, 0, 100),
                        'response_time' => $responseTime,
                    ]);
                }
            }
        } catch (\Exception $e) {
            if ($currentUser instanceof Collection) {
                $currentUser = $currentUser->id;
            }
            RequestLog::create([
                'user_id' => is_int($currentUser) || is_string($currentUser) ? $currentUser : null,
                'company_id' => $companyInfo ?? null,
                'method' => $request->method(),
                'status' => 'Log Error & ' . $response->status(),
                'ip' => request()->ip(),
                'path' => $request->path(),
                'request' => substr(json_encode($request->input()), 0, 4000),
                'response' => substr($response->content(), 0, 2000) . ' ' . substr($e->getMessage(), 0, 1000),
                'remark' => $deviceInfo,
                'timezone' => $timezoneInfo,
                'version' => substr($versionInfo, 0, 100),
                'response_time' => $responseTime,
            ]);
        }
    }
}