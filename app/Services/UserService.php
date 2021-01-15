<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @param $data
     * @return User
     */
    public function create($data): User
    {
        $data['password'] = bcrypt($data['password']);

        return User::create($data);
    }
}
