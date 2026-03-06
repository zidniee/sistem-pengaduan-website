<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SessionApiController extends BaseApiController
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error('Email atau password tidak valid', null, 401);
        }

        $token = Str::random(64);
        Cache::put('api_token:' . $token, $user->id, now()->addHours(8));

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 8 * 60 * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 'Login API berhasil');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], 'Data pengguna berhasil diambil');
    }

    public function logout(Request $request)
    {
        $authHeader = (string) $request->header('Authorization', '');
        if (str_starts_with($authHeader, 'Bearer ')) {
            $token = trim(substr($authHeader, 7));
            if ($token !== '') {
                Cache::forget('api_token:' . $token);
            }
        }

        return $this->success(null, 'Logout API berhasil');
    }
}
