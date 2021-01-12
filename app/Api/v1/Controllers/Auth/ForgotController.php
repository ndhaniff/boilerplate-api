<?php

namespace App\Api\v1\Controllers\Auth;

use App\Api\v1\Requests\Auth\ResetPasswordRequest;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\ResetPasswordLog;
use App\Repositories\UserRepository;
use Dingo\Api\Exception\ResourceException;

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
