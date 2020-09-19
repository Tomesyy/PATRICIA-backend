<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;


class VerifyUser
{
    public function handle($request, Closure $next)
    {
        $token = $request->token;

        if(! $token){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Token not provided'
            ], 400);
        }
        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            print_r($request->route('id'));
            if($decoded->sub != $request->route('id')){
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Unauthorized action'
                ], 400);
            }
            return $next($request);
        } catch(ExpiredException $e){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Token expired'
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Error decoding token'
            ], 400);
        }
        
    }
}