<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private string $secret;
    private int $accessTtl;
    private int $refreshTtl;

    public function __construct()
    {
        $this->secret = config('app.jwt_secret');
        $this->accessTtl = config('app.jwt_access_ttl');
        $this->refreshTtl = config('app.jwt_refresh_ttl');
    }

    public function generateAccessToken(array $payload): string
    {
        return $this->generateToken($payload, $this->accessTtl, 'access');
    }

    public function generateRefreshToken(array $payload): string
    {
        return $this->generateToken($payload, $this->refreshTtl, 'refresh');
    }

    private function generateToken(array $payload, int $ttl, string $type): string
    {
        $now = time();

        $data = array_merge($payload, [
            'iat'  => $now,
            'exp'  => $now + $ttl,
            'typ'  => $type,
        ]);

        return JWT::encode($data, $this->secret, 'HS256');
    }

    public function verify(string $token, string $expectedType): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            if (($decoded->typ ?? null) !== $expectedType) {
                throw new Exception('Invalid token type');
            }

            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception('Invalid or expired token');
        }
    }
}
