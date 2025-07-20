<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'name'=> 'string|required|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'region'=> 'nullable|string|max:255',
            'city'=> 'nullable|string|max:255',
            'password'=> 'required|string|min:8|confirmed',

        ]);

        $user = User::create([

            'name'=>$validate['name'],
            'email'=>$validate['email'],
            'region'=>$validate['region'],
            'city'=>$validate['city'],
            'password'=>bcrypt($validate['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ]);
    } 

public function login(Request $request)
{
    $credentials = $request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
        ]);

    if (!auth()->attempt($credentials)) {
        return response()->json([
            'message'=> 'Invalid credentials',
        ]);}
        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message'=> 'Login successful',
            'user' => $user->only(['name']),
            'token' => $token,
        ]);


}

public function logout(Request $request)
{
    $user = Auth::user();
    $user->tokens()->delete();

    return response()->json([
        'message' => 'User logged out successfully',
    ]);
}

}
