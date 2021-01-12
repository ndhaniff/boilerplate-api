<?php

namespace App\Api\v1\Controllers\Auth;

use App\Repositories\UserRepository;
use App\Api\v1\Requests\Auth\SignInRequest;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class SignInController extends Controller
{
    private static $helper;
    private static $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        self::$helper = $appHelper;
        self::$userRepo = $userRepo;
    }

    public function signin(SignInRequest $request)
    {
        try {
            $credentials = $request->only(['phone', 'email', 'password']);
            if ($request->has('phone')) {
                $credentials['phone'] = self::$userRepo::validatePhone($request->only('phone', 'country_code'));
            }
            
            $user = self::$userRepo->getUserFromEmailOrPhone($credentials);
            $unauthorizedException = new UnauthorizedHttpException('', __('auth.invalid_credential', ['name' => array_key_exists('phone', $credentials) ? 'phone' : 'email']));
            if (!$user) {
                throw $unauthorizedException;
            }

            $isCorrectPassword = Hash::check($credentials['password'], $user->password);
            if (!$isCorrectPassword) {
                throw $unauthorizedException;
            }

            if (config('app.email_verification') && !$user->email_verified_at){
                return response()->json([
                    'message' => __('auth.verify_first')
                ], 200);
            }
            
            $token = $user->generateToken();
            if (!$token) {
                throw $unauthorizedException;
            }
            
            return response()->json([
                'message' => __('auth.success'),
                'data' => [
                    'token' => $token,
                    'user' => $user
                ],
            ], 200);
        } catch(JWTException $e) {
            return response()->json(['token_absent'], $e->getCode());
        }
    }
}
