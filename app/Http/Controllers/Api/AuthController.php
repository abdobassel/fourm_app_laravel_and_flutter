<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userData = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user =  User::create($userData);

        $user->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'user created',
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    // login 
    public function login(LoginRequest $request)
    {

        $request->validated();

        $user = User::whereUsername($request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'invalid crdntial'], 422);
        }
        $token = $user->createToken('auth-token')->plainTextToken;
        return  response([
            'message' => 'Logged',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
