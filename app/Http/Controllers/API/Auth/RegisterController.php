<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterUserRequest;
use App\Services\AuthService;

class RegisterController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * RegisterController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param RegisterUserRequest $request
     * @return array
     */
    public function register(RegisterUserRequest $request): array
    {
        $data = $request->validated();

        $res = $this->authService->registerApi($data);

        return [
            'data' => $res
        ];
    }
}
