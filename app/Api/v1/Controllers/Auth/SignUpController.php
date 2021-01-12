<?php

namespace App\Api\v1\Controllers\Auth;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    private $helper;
    private $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        $this->helper = $appHelper;
        $this->userRepo = $userRepo;
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->all();

        // Check valid OTP
        if (!$this->helper->checkOtp($data['email'], $data['otp'])) {
            throw new ResourceException(__('auth.signup.invalid_otp'));
        }

        return response()->json([
            'message' => __('auth.signup.valid_otp')
        ]);
    }
}
