<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ILLUMINATE\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = auth()->user()->createToken('MyAppToken')->accessToken;
        $cookie = Cookie('token', $token, 1);
        $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_at' => now()->addMinutes(1)->toISOString(), // Ajout de la clé 'expires_at'
            'user' => $user,
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $cookie = Cookie::forget('token');
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200)->withCookie($cookie);
    }

    public function refresh(Request $request)
    {
        if ($request->user()) {
            $token = $request->user()->createToken('MyAppToken')->accessToken;
            return response()->json([
                'token' => $token,
            ])->withCookie('token', $token, 5);
        } else {
            // Si l'utilisateur n'est pas authentifié, renvoyez une réponse appropriée
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    }

    public function getUser(){
        return response([
            'user' => auth()->user()
        ],200);

    }

    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);
        $image = $this->saveImage($request->image,'profiles');

        $id = auth()->user()->id;
        User::where('active', 1)
                    ->where('id', $id)
                    ->update([ 'name' =>  $attrs['name'],
                    'image' => $image]);

        return response([
            'message' => 'User updated',
            'user' => auth()->user()
        ],200);

    }
}
