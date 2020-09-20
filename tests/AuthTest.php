<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class AuthTest extends TestCase
{  
    use DatabaseTransactions;

    public function test_user_cannot_register_with_user_with_existing_mail(){
        $existingUser = User::factory()->create([
            'email'=> $email = 'test@gmail.com'
        ]);

        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => $email,
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ]) -> seeJsonEquals([
            'email' => ['The email has already been taken.']
        ]);
        
    }

    public function test_user_cannot_register_without_password_confirmation(){
        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoe@gmail.com',
            'password' => 'testing'
        ])-> seeJsonEquals([
            'password' => ['The password confirmation does not match.']
        ]);
    }
    
    public function test_user_cannot_register_with_invalid_email(){
        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoegmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])-> seeJsonEquals([
            'email' => ['The email must be a valid email address.']
        ]);
    }

    public function test_user_cannot_register_with_incomplete_required_details(){
        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])-> seeJsonEquals([
            'email' => ['The email field is required.']
        ]);
        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoe@gmail.com',
            'password_confirmation' => 'testing'
        ])-> seeJsonEquals([
            'password' => ['The password field is required.']
        ]);
        $this->post('/api/v1/register', [
            'email' => 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation' => 'testing'
        ])-> seeJsonEquals([
            'name' => ['The name field is required.']
        ]);
    }

    public function test_user_can_register_with_correct_details(){
        $this->post('/api/v1/register', [
            'name' => 'jane doe',
            'email' => 'janedoe@gmail.com',
            'password' => 'testing',
            'password_confirmation'=>'testing'
        ])-> seeJson([
            'status' => 'success',
            'message' => 'User created successfully',
        ]);
    }

    public function test_user_cannot_login_with_incomplete_required_details(){
        $this->post('/api/v1/login', [
            'password' => 'testing'
        ])-> seeJsonEquals([
            'email' => ['The email field is required.']
        ]);
        $this->post('/api/v1/login', [
            'email' => 'janedoe@gmail.com'
        ])-> seeJsonEquals([
            'password' => ['The password field is required.']
        ]);
    }

    public function test_cannot_login_user_that_does_not_exist(){
        $this->post('/api/v1/login', [
            'email' => 'janee@gmail.com',
            'password' => 'testing'
        ])-> seeJsonEquals([
            'status'=>'error',
            'message'=> 'User has not registered'
        ]);
    }

    public function test_cannot_login_user_with_incorrect_password(){
        $existingUser = User::factory()->create([
            'email'=> $email = 'logintest@gmail.com',
            'password' => app('hash')->make('testing')
        ]);

        $this->post('/api/v1/login', [
            'email' => 'logintest@gmail.com',
            'password' => 'testingg'
        ])-> seeJsonEquals([
            'status'=>'error',
            'message'=> 'password is incorrect'
        ]);
    }

    public function test_user_login_successfully(){
        $existingUser = User::factory()->create([
            'email'=> $email = 'logintest@gmail.com',
            'password' => app('hash')->make('testing')
        ]);

        $this->post('/api/v1/login', [
            'email' => 'logintest@gmail.com',
            'password' => 'testing'
        ])-> seeJson([
            'status' => 'success',
            'message' => 'User logged in successfully'
        ]);
    }
}