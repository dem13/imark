<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\Auth\UnauthenticatedException;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return array|void
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|min:2|max:150',
            'password' => 'required|min:5|max:100'
        ]);

        try {
            $res = $this->authService->loginApi($data);

            return [
                'data' => $res,
            ];
        } catch (UnauthenticatedException $e) {
            abort(401);
        }
    }
}
