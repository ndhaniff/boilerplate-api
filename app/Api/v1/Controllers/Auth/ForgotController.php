<?php

namespace App\Api\v1\Controllers\Auth;

use App\Api\v1\Requests\Auth\ForgotRequest;
use App\Api\v1\Requests\Auth\ResetPasswordRequest;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordOtpMail;
use App\Models\ResetPasswordLog;
use App\Repositories\UserRepository;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    private $helper;
    private $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        $this->helper = $appHelper;
        $this->userRepo = $userRepo;
    }

    /**
     * Get OTP on Forgot Password Request
     *
     * @param Request $request
     * @return void
     */
    public function getForgotPasswordOtp(ForgotRequest $request)
    {
        $email = $request->email;
        $otp = $this->helper->getOtp($email, 'forgot_password');

        $message = new ForgetPasswordOtpMail(['otp' => $otp]);
        Mail::to($email)->send($message);

        return response()->json([
            'message' => __('auth.signup.otp_sent'),
        ]);
    }

    /**
     * Reset Password
     *
     * @param ResetPasswordRequest $request
     * @return void
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->all();

        if (!$this->helper->checkOtp($data['email'], $data['otp'])) {
            throw new ResourceException(__('auth.signup.invalid_otp'));
        }

        $this->helper->deleteOtp($data['email'], $data['otp']);

        $user = $this->userRepo->whereEmail($data['email']);
        $user->password = $data['password'];
        $user->save();

        ResetPasswordLog::create([
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return response()->json([
            'message' => __('auth.forget_password.successful'),
        ]);
    }
}