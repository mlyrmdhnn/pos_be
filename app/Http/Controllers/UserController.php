<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(Request $request) {
        $credentials = $request->validate([
            'name' => 'required',
            'username' => 'unique:users,username',
            'password' => 'required',
            'phone' => 'required',
            'role' => 'required'
        ]);
        User::create([
            'name' => $credentials['name'],
            'username' => $credentials['username'],
            'password' => Hash::make($credentials['password']),
            'phone' => $credentials['phone'],
            'role' => $credentials['role']
        ]);
        return response()->json([
            'status' => 'ok',
            'msg' => 'Create user successfully',

        ]);
    }

    public function updateProffile(Request $request) {
        $user = $request->attributes->get('auth_user');
        $credentials = $request->validate([
            'name' => 'required|max:20',
            'phone' => 'required'
        ]);
        $user->update([
            'name' => $credentials['name'],
            'phone' => $credentials['phone']
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'Succes to save changes'
        ]);
    }
}
