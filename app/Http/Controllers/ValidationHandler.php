<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidationHandler extends Controller {
    public function registerValidator(Request $request){
        return $this->validate($request, [
            'name'=> 'required|string',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed'
        ]);
    }
    public function loginValidator(Request $request){
        return $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
    public function updateValidator(Request $request){
        return $this->validate($request, [
            'name'=> 'string',
            'email'=> 'email|unique:users',
        ]);
    }
}