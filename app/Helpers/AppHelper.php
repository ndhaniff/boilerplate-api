<?php

namespace App\Helpers;
use libphonenumber\PhoneNumberUtil;
use App\Helpers\Traits\OtpUtil;
use App\Helpers\Traits\PhoneUtil;

class AppHelper
{
    use PhoneUtil, OtpUtil;

    private static $phoneUtil;

    /**
     * Load all dependencies
     */
    public function __construct()
    {
        self::$phoneUtil = PhoneNumberUtil::getInstance();
    }

}