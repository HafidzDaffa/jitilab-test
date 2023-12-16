<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Error validation login',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password wrong',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            if ($token) {

                return response()->json([
                    'message' => 'Login success',
                    'data' => $user,
                    'token' => $token,
                ], 200);
                
            } else {
                return response()->json([
                    'message' => 'Unauthorized, login failed',
                ], 401);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Sorry, login failed',
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Error validation register',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User register successfully',
                'data' => $user,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User failed to register'
            ], 500);
        }
    }
    
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Logout failed',
                'message' => $th->getMessage(),
            ], 500);
        }
        
        return response()->json([
            'message' => 'Logout success',
        ], 200);
    }
}
