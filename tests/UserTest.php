<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;


    public function test_fetch_all_users(){
        $this->get('/api/v1/users')-> seeJson([
            'status'=> 'success',
            'message'=> 'Fetched all users successfully',
        ]);
    }

    public function test_cannot_get_user_that_does_not_exist(){
        $this->get('/api/v1/users/1000000000000')-> seeJsonEquals([
            'status'=> 'error',
            'message'=> 'User does not exist',
        ]);
    }

    public function test_can_get_single_user_successfully(){
        $user = User::factory()->create([
            'email'=>$email = 'test@gmail.com'
        ]);
        
        $details = User::where('email', $email)->first();

        $this->get('/api/v1/users/'.$details->id)-> seeJson([
            'status'=> 'success',
            'message'=> 'User details found successfully',
        ]);
    }

    public function test_cannot_update_profile_without_token(){
        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $id = json_decode($response)->data->id;

        $this->put('/api/v1/users/'.$id, [
            "name"=> 'jane doe'
        ])-> seeJsonEquals([
            'status'=> 'error',
            'message'=> 'Token not provided'
        ]);

    }

    public function test_cannot_perform_unauthorized_update_action(){
        $user = User::factory()->create([
            'email'=>$email = 'test@gmail.com'
        ]);

        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email2 = 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $details = User::where('email', $email)->first();

        $this->put('/api/v1/users/'.$details->id, [
            "name"=> 'jane doe',
            "token" => json_decode($response)->token
        ])-> seeJsonEquals([
            'status'=> 'error',
            'message'=> 'Unauthorized action'
        ]);
    }

    public function test_cannot_update_email_to_existing_one_or_invalid_one(){
        $user = User::factory()->create([
            'email'=>$email = 'test@gmail.com'
        ]);

        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email2 = 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $details = User::where('email', $email2)->first();

        $this->put('/api/v1/users/'.$details->id, [
            'email'=> $email,
            "token" => json_decode($response)->token
        ])-> seeJsonEquals([
            'email' => ['The email has already been taken.']
        ]);
    }

    public function test_can_update_user_successfully(){
        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email2 = 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $details = User::where('email', $email2)->first();

        $this->put('/api/v1/users/'.$details->id, [
            'email'=> 'newmail@gmail.com',
            "token" => json_decode($response)->token
        ])-> seeJson([
            'status'=> "success",
            'message'=> 'User details updated successfully'
        ]);
    }

    public function test_cannot_delete_profile_without_token(){
        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $id = json_decode($response)->data->id;

        $this->delete('/api/v1/users/'.$id, [
            "name"=> 'jane doe'
        ])-> seeJsonEquals([
            'status'=> 'error',
            'message'=> 'Token not provided'
        ]);
    }

    public function test_cannot_perform_unauthorized_delete_action(){
        $user = User::factory()->create([
            'email'=>$email = 'test@gmail.com'
        ]);

        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email2 = 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $details = User::where('email', $email)->first();

        $this->delete('/api/v1/users/'.$details->id, [
            "name"=> 'jane doe',
            "token" => json_decode($response)->token
        ])-> seeJsonEquals([
            'status'=> 'error',
            'message'=> 'Unauthorized action'
        ]);
    }

    public function test_can_delete_user_successfully(){
        $response = $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email2 = 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])->response->getContent();

        $details = User::where('email', $email2)->first();

        $this->delete('/api/v1/users/'.$details->id, [
            "token" => json_decode($response)->token
        ])-> seeJson([
            'status'=> "success",
            'message'=> 'User deleted successfully'
        ]);
    }

}
