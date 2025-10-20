<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{

    public function login(Request $request){
        try{
            if( !Auth::guard('web')->attempt($request->only('email', 'password')) ) {
                return response()->json([
                    'message' => 'Invalid credentials',
                    'data' => null
                ], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user),
                ]
            ], 200);
    }   catch(Exception $e){
            return response()->json([
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me(){
        try{
            $user = Auth::user();

            return response()->json([
                'message' => 'User retrieved successfully',
                'data' => new UserResource($user),
            ], 200);
            
        }catch(Exception $e){
            return response()->json([
                'message' => 'An error occurred while retrieving user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
