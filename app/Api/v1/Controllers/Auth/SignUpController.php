<?php

namespace App\Api\v1\Controllers\Auth;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class SignUpController extends Controller
{
    private $helper;
    private $userRepo;

    public function __construct(AppHelper $appHelper, UserRepository $userRepo)
    {
        $this->helper = $appHelper;
        $this->userRepo = $userRepo;
    }
}
