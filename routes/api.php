<?php

use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::get('/jwt-test', function (JwtService $jwt) {
    $access = $jwt->generateAccessToken([
        'sub' => 1,
        'email' => 'test@example.com',
    ]);

    $decoded = $jwt->verify($access, 'access');

    return response()->json([
        'token' => $access,
        'decoded' => $decoded,
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::middleware('jwt.auth')->get('/me', function (Request $request) {
$user = $request->attributes->get('auth_user');

    return response()->json([
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'phone' => $user->phone,
        'role' => $user->role,
    ]);
});

Route::middleware(['VerifyServerToken', 'jwt.auth'])->group(function() {
    Route::post('/user/create', [UserController::class, 'createUser']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::post('/user/update', [UserController::class, 'updateProffile']);
    Route::get('/products', [ProductController::class, 'product']);
    Route::get('/product/category', [ProductController::class, 'productCategory']);
});