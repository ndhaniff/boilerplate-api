<?php

namespace App\Api\v1\Controllers\Auth;

use App\Api\v1\Requests\Auth\SignUpRequest;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class SignUpController extends Controller
{
    private static $helper;
    private static $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        self::$helper = $appHelper;
        self::$userRepo = $userRepo;
    }

    /**
     * Sign up the user
     *
     * @param SignUpRequest $request
     * @return void
     */
    public function signup(SignUpRequest $request)
    {   
        $user = self::$userRepo->signUp($request->only(config('boilerplate.sign_up.fields')));

        if($user) {
            $token = $user->generateToken();

            if (config('app.email_verification')) {
                event(new Registered($user));
                return response()->json([
                    'message' => __('auth.signup.successful')
                ]); 
            }

            return response()->json([
                'message' => __('auth.signup.successful'),
                'data' => [
                    'token' => $token,
                    'user' => $user,
                ],
            ]);
        } else {
            abort(422, __('sign_up.fail'));
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json([
            'message' => __('auth.signup.email_verified_successfully'),
        ]);
    }
}
