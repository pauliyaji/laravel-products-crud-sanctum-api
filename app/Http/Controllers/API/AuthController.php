<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
        ]);

        $user = User::create([
            'name' =>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password'])

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token'=> $token,
        ];

        return response($response, 201);   //201 means everything was successful and something was created

    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        // Check Email
        $user = User::where('email', $fields['email'])->first();

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'status'=> 401,
                'message'=> 'Unauthorized, Bad Credentials',
            ]);
        }else{
            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 200);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message'=> 'Logged out successfully'
        ];
    }
}
