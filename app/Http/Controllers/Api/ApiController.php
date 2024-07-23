<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    public function register(Request $request)
    {
        try{
        $validateUser = Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status'=>false,
                'message'=> 'validation error',
                'errors'=>  $validateUser->errors()
            ],401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,   
        ]);

        return response()->json([
            'status'=>true,
            'message'=> 'User Created Successfully',
            'token'=>  $user->createToken("API TOKEN")->plainTextToken
        ],200);
        }
        catch(\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=> $th->getMessage(),
            ],500);  
        }
    }

    public function login(Request $request)
    {
        try
        {
            $validateUser = Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status'=>false,
                'message'=> 'validation error',
                'errors'=>  $validateUser->errors()
            ],422);
        }

        if(!Auth::attempt($request->only(['email', 'password'])))
        {
            return response()->json([
                'status'=>false,
                'message'=> 'Email & Password do not match.',
            ],401);
        }
            $user = User::where('email',$request->email)->first();
            
            return response()->json([
                'status'=>true,
                'message'=> 'User Logged In Successfully',
                'token'=>  $user->createToken("API TOKEN")->plainTextToken
            ],200);
        }
        catch(\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=> $th->getMessage(),
            ],500);  
        }
    }

    public function profile()
    {
        $userData = auth()->user();
        return response()->json([
            'status'=>true,
            'message'=> 'Profile Information',
            'data'=> $userData,
        ],200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>true,
            'message'=> 'User logged out',            
        ],200);
    }
}
