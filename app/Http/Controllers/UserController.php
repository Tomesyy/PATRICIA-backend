<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ValidationHandler;

class UserController extends Controller
{
    public function getAllUsers(Request $request){
        try {
            return response()->json([
                'status'=> "success",
                'message'=> 'Fetched all users successfully',
                'data'=> User::all()
            ],200);

        } catch(Exception $err){
            return response()->json([
                'status' => "error",
                'message' => 'Error fetching all users'
            ], 400);
        }
    }

    public function getUser(Request $request, $id){
        $user = User::find($id);
        if(! $user){
            return response()->json([
                'status' => "error",
                'message' => 'User does not exist'
            ], 400);
        }
        return response()->json([
            'status'=> "success",
            'message'=> 'User details found successfully',
            'data'=> $user
        ],200);
    }

    public function updateUser(Request $request, $id){
        $validator = new ValidationHandler();
        $validator->updateValidator(new Request($request->all()));
        

        $user = User::find($id);
        $user->update($request->all());
        
        return response()->json([
            'status'=> "success",
            'message'=> 'User details updated successfully',
            'data'=> $user
        ],200);

    }

    public function deleteUser(Request $request, $id){
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status'=> "success",
            'message'=> 'User deleted successfully',
            'data'=> $user
        ],200);
    }

}