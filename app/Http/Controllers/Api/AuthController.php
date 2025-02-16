<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login( Request $request ){
        if(! Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function register( Request $request ){
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'message' => 'Register Success',
            'data' => $user,
        ], 201);

    }

    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ], 200);
    }

}
