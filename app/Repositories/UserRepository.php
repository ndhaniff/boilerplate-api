<?php

namespace App\Repositories;

use App\Models\User;
use Ndhaniff\MakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
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
        return User::when(array_key_exists('email', $credentials), function($q) use ($credentials) {
            $q
             ->where('email', $credentials['email'])
             ->whereNotNull('email_verified_at');
         }, function ($q) use ($credentials) {
             $q->where('phone', $credentials['phone']);
         })->first();
    }
}