<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;


class ApiController extends Controller
{
    //register
    public function register(Request $request){
        try{
        $validateUser = Validator::make($request->all(),
    [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
        'role' => 'required',
        'nim_or_nip' => 'required|unique:users,nim_or_nip',  ]
    );
    if($validateUser->fails()){
        return response()->json([
            'status'=> false,
            'message' => 'validation error',
            'errors' => $validateUser->errors()
        ],401);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role,
        'nim_or_nip' => $request->nim_or_nip

    ]);
    return response()->json([
        'status'=> true,
        'message' => 'Register sukses',
        'token' => $user->createToken("API TOKEN")->plainTextToken,
        'data' => $user
    ],200);
}catch (\Throwable $th){
    return response()->json([
        'status'=> false,
        'message' => $th->getMessage(),500]);
}
   
    }


    //login
    public function login(Request $request){
        try{
            
        $validateUser = Validator::make($request->all(),
        [
           
            'nim_or_nip' => 'required|exists:users,nim_or_nip',
            'password' => 'required'
        ]
        );
        if($validateUser->fails()){
            return response()->json([
                'status'=> false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ],401);
        }
        if(!Auth::attempt($request->only(['nim_or_nip','password']))){
            
            return response()->json([
                'status'=> false,
                'message' => 'nim_or_nip& password salah',
            ],401);
        }
        $user = User::where('nim_or_nip',$request->nim_or_nip)->first();
        return response()->json([
            'status'=> true,
            'message' => 'Login sukses',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'data' => $user

        ],200);

        }catch (\Throwable $th){
            return response()->json([
                'status'=> false,
                'message' => $th->getMessage(),500]);
        }
}

//profile 

public function Profile(){
    $userData = auth()->user();
    return response()->json([
        'status'=> true,
        'message' => 'Profile Informasi',
        'data' => $userData,
        'id' => auth()->user()->id
    ],200);
}

//logout
public function Logout(){
    auth()->user()->tokens()->delete();
    return response()->json([
        'status'=> true,
        'message' => 'User logged out',
        'data' => [],
    ],200);
}

//emailcheck
public function checkEmail(Request $request)
{
    try {
        $email = $request->input('email');

        if ($user = User::where('email', $email)->first()) {
            // Generate API token
            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Email verified',
                'data' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email not found',
            ], 404);
        }
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}



public function showUserByNimOrNip(Request $request)
{
    try {
        $nim_or_nip = $request->input('nim_or_nip');
        if ($user = User::where('nim_or_nip', $nim_or_nip)->first()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditemukan',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data user tidak ditemukan'
            ], 404);
        }
    } catch (Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}



}
