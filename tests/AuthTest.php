<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class AuthTest extends TestCase
{  
    use DatabaseMigrations;

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

    public function test_user_cannote_register_with_incomplete_required_details(){
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
}