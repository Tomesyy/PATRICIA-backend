<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Firebase\JWT\JWT;
use App\Http\Controllers\ValidationHandler;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function registerUser(Request $request){
        $validator = new ValidationHandler();
        $validator->registerValidator(new Request($request->all()));

        try {
            $newUser = User::create([
                'name' => $request->name, 
                'email'=> $request->email,
                'password' => app('hash')->make($request->password)
            ]);
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

    /**
     * login a user.
     *
     * @param  Request  $request
     * @return Response
     */

    public function loginUser(Request $request){
        $validator = new ValidationHandler();
        $validator->loginValidator(new Request($request->all()));

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
            ], 401);
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
            'exp' => time() + 60*30
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
}
