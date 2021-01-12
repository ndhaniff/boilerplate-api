<?php
namespace App\Helpers\Traits;
use App\Models\Otp;
use App\Models\OtpLog;
use Carbon\Carbon;

trait OtpUtil 
{
    
    /**
     * Generate OTP code
     * @param string $email
     * @param string $action
     */
    public function getOtp($email, $action = null)
    {
        $code = mt_rand(100000, 999999);
        while (Otp::where('code', '=', $code)->count() > 0 || $code == '999999') {
            $code = mt_rand(100000, 999999);
        }
        Otp::where('email', '=', $email)
            ->delete();
        Otp::create([
            'email' => $email,
            'code' => $code
        ]);
        OtpLog::create([
            'action' => $action,
            'code' => $code,
            'email' => $email,
        ]);
        return $code;
    }

    /**
     * Check OTP is valid
     * @param string $email
     * @param string $code
     */
    public function checkOtp($email, $code)
    {
        $otp = Otp::where('email', '=', $email)
            ->where('code', '=', $code)
            ->first();
        if (!$otp) {
            return false;
        }

        // 10 mins
        $passed = Carbon::parse($otp->updated_at)->addSeconds(600)->isPast();
        if ($passed) {
            $otp->delete();
            return false;
        }

        return true;
    }

    /**
     * Delete OTP
     *
     * @param string $email
     * @param string $code
     * @return void
     */
    public function deleteOtp($email, $code)
    {
        Otp::where('email', '=', $email)
            ->where('code', '=', $code)
            ->delete();
        return true;
    }
}