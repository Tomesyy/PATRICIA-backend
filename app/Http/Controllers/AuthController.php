<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function registerUser(Request $request){
        //validator
        $this->validate($request, [
            'name'=> 'required|string',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed'
        ]);

        try {
            $newUser = new User;
            $newUser->name = $request->input('name');
            $newUser->email = $request->input('email');
            $password = $request->input('password');
            $newUser->password = app('hash')->make($password);

            $newUser->save();
            $token = $this->generateToken($newUser);

            return response()->json([
                'status' => "success",
                'token' => $token,
                'message' => 'User created successfully',
                'data' => $newUser
            ], 201);

        } catch(Exception $err) {
            return response()->json([
                'status' => "error",
                'message' => 'User registration failed'
            ], 400);
        }

    }

    public function loginUser(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $userDetails = User::where('email', $request->input('email'))->first();

        if(! $userDetails){
            return response()->json([
                'status'=>"error",
                'message'=> "User has not registered"
            ], 400);
        }

        if(! Hash::check($request->input('password'), $userDetails->password)){
            return response()->json([
                'status'=>"error",
                'message'=> "password is incorrect"
            ], 400);
        }
        
        return response()->json([
            'status' => "success",
            'token' => $this->generateToken($userDetails),
            'message' => 'User logged in successfully',
            'data' => $userDetails
        ], 200);

    }

    protected function generateToken(User $user){
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60*10
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
}
