<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = (string) $request->header('Authorization', '');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $token = trim(substr($authHeader, 7));
        if ($token === '') {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $userId = Cache::get('api_token:' . $token);
        if (!$userId) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
