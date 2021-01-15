<?php


namespace App\Services;


use App\Exceptions\Auth\UnauthenticatedException;
use Auth;

class AuthService
{
    /**
     * Login in user account via API
     *
     * @param array $data
     * @throws UnauthenticatedException
     */
    public function loginApi(array $data)
    {
        if(!Auth::attempt($data)) {
            throw new UnauthenticatedException();
        }

        $user = Auth::user();

        return [
            'user' => $user,
            'access_token' => $user->createToken('api')->plainTextToken,
        ];
    }
}
