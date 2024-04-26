<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Traits\HasHelper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HasHelper;

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        return response()->success(
            'Registration Successful',
            new UserResource($user)
        );
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $this->getCredentials($request);
            $user = $this->findUser($credentials);

            if (!$user || !$this->validatePassword($request, $user)) {
                return response()->failed(
                    'Failed to validate Password'
                );
            }

            $token = $this->generateToken($user);

            return response()->success(
                'Login Successful',
                [
                    'user' => new UserResource($user),
                    'bearer_token' => $token
                ]
            );
        } catch (JWTException) {
            return response()->failed(
                'Login Failed'
            );
        }
    }

    public function profile()
    {
        $userData = auth()->user();

        return response()->success(
            'Profile Data',
            new UserResource($userData)
        );
    }

    public function refreshToken()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());

        return response()->success(
            'Refresh Token',
            [
                'token' => $newToken,
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]
        );
    }

    public function logout()
    {
        $token = JWTAuth::parseToken()->getToken();
        if ($token) {
            try {
                $userData = auth()->user();

                JWTAuth::invalidate($token);
                return response()->success(
                    'Logout Successful',
                    new UserResource($userData)
                );
            } catch (JWTException) {
                return response()->failed();
            }
        }
    }
}
