<?php

use Dingo\Api\Routing\Router;

$api = app('Dingo\Api\Routing\Router');
$v1CtrlNamespace = 'App\Api\v1\Controllers';
$v2CtrlNamespace = 'App\Api\v2\Controllers';

$api->version('v1', function ($api) use ($v1CtrlNamespace) {
    $api->group(['prefix' => 'auth', 'namespace' => $v1CtrlNamespace], function (Router $api) {
        // Guest Routes
        $api->post('signin', 'Auth\SignInController@signin');
        $api->post('signup', 'Auth\SignUpController@signup');
        $api->get('email/verify/{id}/{hash}', 'Auth\SignUpController@verifyEmail')->name('verification.verify');

        // Forgot Password
        $api->group(['prefix' => 'forget'], function (Router $api) {
            $api->post('/', 'Auth\ForgotController@resetPassword')->name('forget_password');
        });

        $api->group(['prefix' => 'otp'], function(Router $api) {
            $api->group([
                'middleware' => ['api.throttle'],
                'limit' => 1, 'expires' => 0.5
            ], function ($api) {
                $api->post('/', 'Shared\OtpController@getOtp')->name('opt.get_otp');
            });
            $api->post('/verify', 'Shared\OtpController@verifyOtp')->name('opt.verify_otp');
        });
    });

    // Protected Routes
    $api->group(['middleware' => ['jwt.auth', 'api.log'], 'namespace' => $v1CtrlNamespace], function($api) {
        // Access Control List
        $api->group(['prefix' => 'acl'], function (Router $api) {
            $api->post('/assign', 'ACL\AccessGroupsController@assign')->name('acl.assign');
        });
    });
});