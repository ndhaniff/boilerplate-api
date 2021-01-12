<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use App\Models\User;
use Dingo\Api\Exception\ResourceException;
use Ndhaniff\MakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    private static $helper;

    public function __construct(AppHelper $helper)
    {
        self::$helper = $helper;
        $this->makeModel();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function getUserFromEmailOrPhone($credentials)
    {
        return $this->model->when(array_key_exists('email', $credentials), function($q) use ($credentials) {
            $q
             ->where('email', $credentials['email'])
             ->whereNotNull('email_verified_at');
         }, function ($q) use ($credentials) {
             $q->where('phone', $credentials['phone']);
         })->first();
    }

    public function signUp($data)
    {
        $data['phone'] = self::validatePhone([
            'phone' => $data['phone'], 
            'country_code' => $data['country_code']
        ]);

        if (!$this->model->whereEmail($data['email'])->exists()) {
            return $this->model->firstOrCreate($data);
        } else {
            throw new ResourceException(__('auth.sign_up.already_exist'));
        }
    }

    public function whereEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public static function validatePhone($validateData)
    {
        if (self::$helper->validatePhoneNumber($validateData['phone'], $validateData['country_code'])) {
            return self::$helper->convertPhoneNumber($validateData['phone'], $validateData['country_code']);
        } else {
            throw new ResourceException(__('auth.login_failed'), ['phone' => __('auth.invalid_phone')]);
        }
    }
}