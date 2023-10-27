<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function CreateUser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required|min:4',
            'email' =>'required|email|unique:users',
            'password' =>'required|min:6',
	     'confirm_password' =>'required|same:password',

            ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors(),422]);
        }
        $user =User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
        ]);
        return response()->json(['message' =>'User registered successfully', 'token' => $user->createToken("API token")->plainTextToken],201);
    }


    public function LoginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
	if(Auth::attempt($credentials)){
		$user= Auth::user();
		return response()->json(['message' =>'User logged in successfully'],200);
	}else{
		return response()->json(['error' => 'Invalid Credentials'],401);
	     }
        
    }
}
