<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request, JwtService $jwt)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect username or password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = $jwt->generateAccessToken([
            'sub' => $user->id,
            'username' => $user->username,
        ]);

        $refreshToken = $jwt->generateRefreshToken([
            'sub' => $user->id,
        ]);

        $sessionId = Str::uuid();

        $user->current_session_id = $sessionId;

        $user->save();



        return response()
            ->json([
                'message' => 'Login success',
            ])
            ->withCookie(
                cookie(
                    name: 'access_token',
                    value: $accessToken,
                    minutes: config('app.jwt_access_ttl') / 60,
                    path: '/',
                    domain: null,
                    secure: false,
                    httpOnly: true,
                    raw: false,
                    sameSite: 'Lax'
                )
            )
            ->withCookie(
                cookie(
                    name: 'refresh_token',
                    value: $refreshToken,
                    minutes: config('app.jwt_refresh_ttl') / 60,
                    path: '/',
                    domain: null,
                    secure: false,
                    httpOnly: true,
                    raw: false,
                    sameSite: 'Lax'
                )
            )
            ->withCookie(
               cookie(
                name : 'session_id',
                value : $sessionId,
                minutes : config('app.jwt_refresh_ttl') / 60,
                path : '/',
                domain : null,
                secure : false,
                httpOnly : true,
                raw : false,
                sameSite : 'lax'
               )
            );
    }
    public function refresh(Request $request, JwtService $jwt)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (! $refreshToken) {
            return response()->json([
                'message' => 'Refresh token missing'
            ], 403);
        }

        try {
            $payload = $jwt->verify($refreshToken, 'refresh');

            $user = \App\Models\User::find($payload['sub'] ?? null);

            if (! $user) {
                return response()->json([
                    'message' => 'User not found'
                ], 403);
            }

            $newAccessToken = $jwt->generateAccessToken([
                'sub' => $user->id,
                'email' => $user->email,
            ]);

            return response()
                ->json([
                    'message' => 'Access token refreshed'
                ])
                ->withCookie(
                    cookie(
                        name: 'access_token',
                        value: $newAccessToken,
                        minutes: config('app.jwt_access_ttl') / 60,
                        path: '/',
                        domain: null,
                        secure: false,
                        httpOnly: true,
                        raw: false,
                        sameSite: 'Lax'
                    )
                );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid refresh token'
            ], 403);
        }
    }

    public function logout(Request $request)
    {


        return response()
            ->json([
                'message' => 'Logged out'
            ])
            ->withCookie(
                cookie(
                    name: 'access_token',
                    value: '',
                    minutes: -1,
                    path: '/',
                    domain: null,
                    secure: false,
                    httpOnly: true,
                    sameSite: 'Lax'
                )
            )
            ->withCookie(
                cookie(
                    name: 'refresh_token',
                    value: '',
                    minutes: -1,
                    path: '/',
                    domain: null,
                    secure: false,
                    httpOnly: true,
                    sameSite: 'Lax'
                )
            )->withCookie(
                cookie(
                    name: 'access_token',
                    value: '',
                    minutes: -1,
                    path: '/',
                    domain: null,
                    secure: false,
                    httpOnly: true,
                    sameSite: 'Lax'
                )
            );
    }

    public function changePassword(Request $request) {

        $request->validate([
            'current_pass' => ['required'],
            'new_pass' => ['required', 'min:8', 'different:current_pass'],
        ]);

        $user = $request->attributes->get('auth_user');

        if (!Hash::check($request->current_pass, $user->password)) {
            return response()->json([
                'message' => 'Incorrect current password'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_pass)
        ]);

        return response()->json([
            'message' => 'Password successfully updated'
        ], 200);
    }



}
