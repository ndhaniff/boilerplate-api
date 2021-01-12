<?php

use Dingo\Api\Routing\Router;

$api = app('Dingo\Api\Routing\Router');
$v1CtrlNamespace = 'App\Api\v1\Controllers';
$v2CtrlNamespace = 'App\Api\v2\Controllers';

$api->version('v1', function ($api) use ($v1CtrlNamespace) {
    $api->group(['prefix' => 'auth', 'namespace' => $v1CtrlNamespace], function (Router $api) {
        // Protected Routes
        $api->group(['middleware' => 'auth'], function($api) {
            // $api->post('signup', 'AuthController@signup');
        });
        // Guest Routes
        $api->post('signin', 'Auth\SignInController@signin');
        $api->post('signup', 'Auth\AuthController@signup');

        // Forgot Password
        $api->group(['prefix' => 'forget'], function (Router $api) {
            $api->group([
                'middleware' => ['api.throttle'],
                'limit' => 1, 'expires' => 0.5
            ], function ($api) {
                $api->post('otp', 'Auth\ForgotController@getForgotPasswordOtp')->name('forget_password.get_otp');
            });
            $api->post('otp/verify', 'Auth\SignUpController@verifyOtp')->name('forget_password.verify_otp');
            $api->post('/', 'Auth\ForgotController@resetPassword')->name('forget_password');
        });
    });
});