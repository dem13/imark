<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * @param Request $request
     * @return UserResource
     */
    public function index(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
