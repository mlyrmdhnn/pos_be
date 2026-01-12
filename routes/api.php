<?php

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

// Route::get('/jwt-test', function (JwtService $jwt) {
//     $access = $jwt->generateAccessToken([
//         'sub' => 1,
//         'email' => 'test@example.com',
//     ]);

//     $decoded = $jwt->verify($access, 'access');

//     return response()->json([
//         'token' => $access,
//         'decoded' => $decoded,
//     ]);
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::post('/transactions', [CheckoutController::class, 'create']);

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

Route::middleware(['jwt.auth','VerifyServerToken' ])->group(function() {
    Route::post('/user/create', [UserController::class, 'createUser']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::post('/user/update', [UserController::class, 'updateProffile']);
    Route::get('/products', [ProductController::class, 'product']);
    Route::get('/product/category', [ProductController::class, 'productCategory']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/detail/{prd}', [ProductController::class, 'detail']);
    Route::post('/product/edit', [ProductController::class, 'edit']);
    Route::post('/product/delete', [ProductController::class, 'destroy']);
    Route::get('/products/category', [ProductController::class, 'byCategory']);
});