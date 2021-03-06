<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

/**
 * Verify that user is authorized to perform actions with token.
 */

class VerifyUser
{
    public function handle($request, Closure $next)
    {
        $token = $request->token;

        if(! $token){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Token not provided'
            ], 401);
        }
        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

            if($decoded->sub != $request->route('id')){
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Unauthorized action'
                ], 401);
            }
            return $next($request);
        } catch(ExpiredException $e){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Token expired'
            ], 401);
        }catch(Exception $e){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Error decoding token'
            ], 401);
        }
        
    }
}