<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Validation controller for post, put requests.
 */

class ValidationHandler extends Controller {
    /**
     * Validate user register request.
     * 
     * @param Request  $request
     */

    public function registerValidator(Request $request){
        return $this->validate($request, [
            'name'=> 'required|string',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed'
        ]);
    }

    /**
     * Validate user login request.
     * 
     * @param Request  $request
     */
    public function loginValidator(Request $request){
        return $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }

    /**
     * Validate user update request.
     * 
     * @param Request  $request
     */
    public function updateValidator(Request $request){
        return $this->validate($request, [
            'name'=> 'string',
            'email'=> 'email|unique:users',
        ]);
    }
}