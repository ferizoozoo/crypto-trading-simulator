<?php

namespace App\Controller\Services;

use App\Entities\User;

interface UserServiceInterface
{
    public function register(User $user);
}
