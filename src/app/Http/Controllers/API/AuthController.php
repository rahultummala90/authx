<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|string|in:user,admin', // optional role
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // default role
        ]);

        // Assign token scopes based on role
        $scopes = $user->role === 'admin' ? ['admin'] : ['user'];

        $token = $user->createToken('authx-token', $scopes)->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'scopes' => $scopes,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Assign token scopes based on role
        $scopes = $user->role === 'admin' ? ['admin'] : ['user'];

        $token = $user->createToken('authx-token', $scopes)->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'scopes' => $scopes,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out']);
    }
}
