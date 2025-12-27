<?php
namespace App\Http\Middleware;

use Closure;

class VerifyServerToken
{
    public function handle($request, Closure $next)
    {
        $serverToken = $request->header('X-Server-Auth');

        if (!$serverToken || $serverToken !== env('SERVER_SECRET')) {
            return response()->json(['error' => 'Token Missing'], 403);
        }

        return $next($request);
    }
}
