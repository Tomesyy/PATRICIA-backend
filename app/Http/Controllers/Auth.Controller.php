<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request){
        //validator
        $this.validate($request, [
            'name'=> 'required|string',
            'email'=> 'required|email|unique|users',
            'password'=> 'required|confirmed'
        ]);

        $newUser = new User;
        $newUser->name = $request->input('name');
        $newUser->email = $request->input('email');
        $password = $request->input('password');
        $newUser->password = app('hash')->make($password);

        $newUser->save();
    }
}
