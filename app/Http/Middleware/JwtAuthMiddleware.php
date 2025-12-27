<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');

        if (! $token) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $jwt = app(JwtService::class);
            $payload = $jwt->verify($token, 'access');

            $user = User::find($payload['sub'] ?? null);

            if (! $user) {
                return response()->json([
                    'message' => 'User not found'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // inject user ke request (BUKAN session)
            $request->attributes->set('auth_user', $user);

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid or expired token'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
