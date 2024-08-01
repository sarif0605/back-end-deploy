<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserloginRequest;
use App\Http\Requests\User\UserRegisternRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(UserloginRequest $request)
    {
        $data = $request->validated();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userData = User::with('role')->where('email', $data['email'])->first();
        $token = JWTAuth::fromUser($userData);
        return response()->json([
            "message" => "Login Berhasil",
            "user" => new UserResource($userData),
            "token" => $token
        ]);
    }

    public function register(UserRegisternRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $roleUser = Roles::where('name', 'user')->first();
        if (!$roleUser) {
            return response()->json(['message' => 'Role user tidak ditemukan.'], 500);
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $roleUser->id,
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }

    public function logout() : JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Logout Berhasil']);
    }

    public function getUser() : JsonResponse
    {
        $user = auth()->user();
        $currentUser = User::with('role')->find($user->id);
        return response()->json([
            "message" => "berhasil get user",
            "data" => new UserResource($currentUser)
        ]);
    }
}
