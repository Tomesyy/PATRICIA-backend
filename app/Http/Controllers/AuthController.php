<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\User;

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

            return response()->json([
                'status' => 200,
                'message' => 'User created successfully',
                'data' => $newUser
            ], 200);

        } catch(\Exception $err) {
            return response()->json([
                'status' => 400,
                'message' => 'User registration failed'
            ], 400);
        }

    }
}
