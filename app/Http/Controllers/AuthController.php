<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterStoreRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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

    public function logout(Request $request){
        try{
            $user = Auth::user();
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful',
                'data' => null,
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(RegisterStoreRequest $request){
        
        $data = $request->validated();

        DB::beginTransaction();
        try{
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return response()->json([
                'message' => 'Registration successful',
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user),
                ],
            ], 201);    

        }catch( Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);

        }
    }
}
