<?php


namespace App\Services;


use App\Exceptions\Auth\UnauthenticatedException;
use App\Models\User;
use Auth;

class AuthService
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Login in user account via API
     *
     * @param array $data
     * @throws UnauthenticatedException
     */
    public function loginApi(array $data): array
    {
        if(!Auth::attempt($data)) {
            throw new UnauthenticatedException();
        }

        $user = Auth::user();

        return [
            'user' => $user,
            'access_token' => $this->createToken($user),
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function registerApi(array $data): array
    {
        $user = $this->register($data);

        return [
            'user' => $user,
            'access_token' => $this->createToken($user)
        ];
    }

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return $this->userService->create($data);
    }

    /**
     * @param User $user
     * @return string
     */
    private function createToken(User $user): string
    {
        return $user->createToken('api')->plainTextToken;
    }
}
