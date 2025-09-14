<?php

namespace App\Http\Controllers\Api\Farhad;

use App\Models\User;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use apiresponse;

    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'nullable|string|max:255',
            'username'     => 'nullable|string|max:255|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = User::create([
                'name'         => $request->name,
                'username'     => $request->username,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'password'     => Hash::make($request->password),
                'is_admin'     => 0,
                'status'       => 1,
            ]);

            $token = JWTAuth::fromUser($user);

            return $this->success(
                $this->formatToken($token),
                'Registration successful',
                200
            );
        } catch (\Exception $e) {
            return $this->error(false, 'Registration failed: ' . $e->getMessage(), 500);
        }
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error(false, 'Invalid email or password', 401);
            }

            $user = auth()->user();

            // Prevent admin from logging in via API
            if ($user->is_admin) {
                return $this->error(false, 'Unauthorized', 403);
            }

            return $this->success(
                $this->formatToken($token),
                'Login successful',
                200
            );
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->error(false, 'Token expired', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->error(false, 'Token invalid', 401);
        } catch (\Exception $e) {
            return $this->error(false, 'Login failed: ' . $e->getMessage(), 500);
        }
    }

    // LOGOUT
    public function logout()
    {
        try {
            $user = JWTAuth::user();
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success(
                ['user_name' => $user->name],
                'Successfully logged out',
                200
            );
        } catch (\Exception $e) {
            return $this->error(false, 'Logout failed: ' . $e->getMessage(), 500);
        }
    }

    // FORMAT TOKEN
    protected function formatToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60, // expiry in seconds
        ];
    }

    // TEST ROUTE
    public function test()
    {
        return $this->success([], 'Hello, World', 200);
    }
}
