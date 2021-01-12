<?php
namespace App\Helpers\Traits;
use libphonenumber\PhoneNumberFormat;

trait PhoneUtil 
{
    /**
     * Check if the phone number is valid
     * @param string $phone
     * @param string $countryCode
     * @return boolean|null
     */
    public static function validatePhoneNumber($phone, $countryCode)
    {
        try {
            $numberPrototype = self::$phoneUtil->parse($phone, $countryCode);
            return self::$phoneUtil->isValidNumber($numberPrototype);
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Convert phone to international format
     * @param string $phone
     * @param string $countryCode
     */
    public static function convertPhoneNumber($phone, $countryCode)
    {
        try {
            $numberPrototype = self::$phoneUtil->parse($phone, $countryCode);
            return self::$phoneUtil->format($numberPrototype, PhoneNumberFormat::INTERNATIONAL);
        } catch(\Exception $e) {
            return null;
        }
    }
}