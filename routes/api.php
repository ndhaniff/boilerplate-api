<?php

$api = app('Dingo\Api\Routing\Router');
$v1CtrlNamespace = 'App\Api\v1\Controllers';
$v2CtrlNamespace = 'App\Api\v2\Controllers';

$api->version('v1', function ($api) use ($v1CtrlNamespace) {
    $api->group(['prefix' => 'auth', 'namespace' => $v1CtrlNamespace], function ($api) {
        // Protected Routes
        $api->group(['middleware' => 'auth'], function($api) {
            // $api->post('signup', 'AuthController@signup');
        });
        // Guest Routes
        $api->post('signin', 'SignInController@signin');
        $api->post('signup', 'AuthController@signup');
    });
});