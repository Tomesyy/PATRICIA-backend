<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers(Request $request){
        try {
            return response()->json([
                'status'=> 200,
                'message'=> 'Fetched all users successfully',
                'data'=> User::all()
            ],200);

        } catch(\Exception $err){
            return response()->json([
                'status' => 400,
                'message' => 'Error fetching all users'
            ], 400);
        }
    }

    public function getUser(Request $request, $id){
        $user = User::find($id);
        if(! $user){
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist'
            ], 400);
        }
        return response()->json([
            'status'=> 200,
            'message'=> 'User details found successfully',
            'data'=> $user
        ],200);
    }

    public function updateUser(Request $request, $id){
        $this->validate($request, [
            'name'=> 'string',
            'email'=> 'email|unique:users',
        ]);

        $user = User::find($id);
        if(! $user){
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist'
            ], 400);
        }
        $user->update($request->all());
        
        return response()->json([
            'status'=> 200,
            'message'=> 'User details updated successfully',
            'data'=> $user
        ],200);

    }

    public function deleteUser(Request $request, $id){
        $user = User::find($id);
        if(! $user){
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist'
            ], 400);
        }
        $user->delete();

        return response()->json([
            'status'=> 200,
            'message'=> 'User deleted successfully',
            'data'=> $user
        ],200);
    }

}