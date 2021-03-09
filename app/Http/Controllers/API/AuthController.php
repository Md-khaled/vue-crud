<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use GuzzleHttp\Client;

class AuthController extends Controller
{

    public function register(Request $request){
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $token], 201);
    }
    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'email|required',
            'password' => 'required'
        ]);
        $loginData=[
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!auth()->attempt($loginData)) {
            return response()->json([
                'success' => false,
                'message' => 'The credentials doesn\'t match'
            ], 200);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['success'=>true,'user' => auth()->user(), 'access_token' => $accessToken]);
    }
    public function logout(Request $request)
    {

        return \Auth::user()->token()->revoke();
    }
}
