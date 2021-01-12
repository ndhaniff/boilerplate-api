<?php

namespace App\Api\v1\Controllers\Shared;

use App\Api\v1\Requests\Auth\OtpRequest;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Repositories\UserRepository;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    private $helper;
    private $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        self::$helper = $appHelper;
        self::$userRepo = $userRepo;
    }
    
    public function getOtp(OtpRequest $request)
    {
        $email = $request->email;
        // Get OTP
        $otp = self::$helper->getOtp($email, 'signup');

        // Send OTP
        $message = (new OtpMail(['otp' => $otp]));
        Mail::to($email)->send($message);

        return response()->json([
            'message' => __('auth.signup.otp_sent')
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->all();

        // Check valid OTP
        if (!self::$helper->checkOtp($data['email'], $data['otp'])) {
            throw new ResourceException(__('auth.signup.invalid_otp'));
        }

        return response()->json([
            'message' => __('auth.signup.valid_otp')
        ]);
    }
}
