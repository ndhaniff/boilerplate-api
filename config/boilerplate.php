<?php

use Carbon\Carbon;

return [

    'sign_up' => [

        'rules' => [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required',
            'country_code' => 'required',
            'dob' => 'sometimes|before:' . Carbon::now()->toDateString(),
            'avatar' => 'sometimes|url',
        ]
    ],
    'sign_in' => [

        'rules' => [
            'email' => 'required_without_all:phone|email',
            'phone' => 'required_without_all:email',
            'country_code' => 'required_with:phone',
            'password' => 'required',
        ]
    ],
    'forgot' => [
        'rules' => [
            'email' => 'required|email'
        ]
    ],
    'reset_password' => [
        'rules' => [
            'email' => 'required|email',
            'password' => 'required',
            'otp' => 'required'
        ]
    ]
];