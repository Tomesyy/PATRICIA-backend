<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ValidationHandler;

class UserController extends Controller
{
    /**
     * Fetch all registerd users users.
     *
     * @param  Request  $request
     * @return Response
     */
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

    /**
     * Get details of a single user.
     *
     * @param  Request  $request
     * @param $id - id of user
     * @return Response
     */

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

    /**
     * update details of a single user.
     *
     * @param  Request  $request
     * @param $id - id of user
     * @return Response
     */

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
    /**
     * Delete a single user.
     *
     * @param  Request  $request
     * @param $id - id of user
     * @return Response
     */

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