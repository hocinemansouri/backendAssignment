<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:2|max:100',
            'surname' => 'required',
            'nickname' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'error' => $validator->errors(),
            ],422);
        }
        $fname = $request->name;
        $lname = $request->surname;
        $username = strtolower($lname.(substr($fname,0,3)));
        $user = User::create([
            'name'=>$fname,
            'surname' => $lname,
            'username'=>$username,
            'nickname' => $request->nickname,
            'role' => $request->role,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            
        ]);

        return response()->json([
            'message' => 'Registration succesful',
            'data' => $user,
        ],200); 

    }

    public function login(Request $request){
        
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => 'Login failed',
                'error' => $validator->errors(),
            ],422);
        }

        $user = User::where('email',$request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'loggin succesful',
                    'token' => $token,
                    'data' => $user
                ],200);
            }else{
                return response()->json([
                    'message' => 'Incorrect credentials'
                ],400);
            }
        }else{
            return response()->json([
                'message' => 'Incorrect credentials'
            ],400);
        }
        return response()->json([
            'message' => 'logging'
        ]);
    }

    public function user (Request $request){
        return response()->json([
            'message' => 'User fetched succesfully',
            'data' => $request->user(),
        ],200);
    }

    public function logout (Request $request){
            $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out succesfully',
        ],200);
    }

}