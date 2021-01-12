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
    private $helper;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        $this->helper = $appHelper;
        $this->userRepo = $userRepo;
    }

    public function signin(SignInRequest $request)
    {
        try {
            $credentials = $request->only(['phone', 'email', 'password']);
            if ($request->has('phone')) {
                $credentials['phone'] = $this->validatePhone($request->only('phone', 'country_code'));
            }
            
            $user = $this->userRepo->getUserFromEmailOrPhone($credentials);
            $unauthorizedException = new UnauthorizedHttpException('', __('auth.invalid_credential', ['name' => array_key_exists('phone', $credentials) ? 'phone' : 'email']));
            if (!$user) {
                throw $unauthorizedException;
            }

            $isCorrectPassword = Hash::check($credentials['password'], $user->password);
            if (!$isCorrectPassword) {
                throw $unauthorizedException;
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

    public function validatePhone($validateData)
    {
        if ($this->helper->validatePhoneNumber($validateData['phone'], $validateData['country_code'])) {
            return $this->helper->convertPhoneNumber($validateData['phone'], $validateData['country_code']);
        } else {
            throw new ResourceException(__('auth.login_failed'), ['phone' => __('auth.invalid_phone')]);
        }
    }
}
