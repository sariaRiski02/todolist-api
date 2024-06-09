<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function test_User_Register_success()
    {
        $response = $this->post('/api/users/register', [
            'name' => "test",
            'email' => "test123@test.com",
            'password' => 'test123'
        ]);

        $user = User::first();
        // $response->assertStatus(200);
        $response->assertJson([
            "data" => [
                "id" => $user->id,
                "name" => "test",
                "email" => "test123@test.com",
            ]
        ]);
    }
    public function test_User_Register_missing_form()
    {
        $response = $this->post('/api/users/register', [
            'name' => "",
            'email' => "test123@test.com",
            'password' => 'test123'
        ]);
        // $response->assertStatus(400);
        $response->assertJson([
            "errors" => [

                "name" => [
                    "The name field is required."
                ]
            ]
        ]);
    }

    public function test_User_Register_email_exists()
    {
        $this->seed([UserSeeder::class]);

        $response = $this->post('/api/users/register', [
            'name' => 'test-1',
            'email' => 'test-1@test.com',
            'password' => 'test-1'
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            "errors" => [
                "email" => [
                    "The email has already been taken."
                ]

            ]

        ]);
    }


    // test for login

    public function test_user_login_success()
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/users/login', [
            'email' => 'test-' . 1 . '@test.com',
            'password' => 'test-' . 1
        ]);

        $user = User::where('name', 'test-1')->first();


        $response->assertJson([
            "data" => [
                "id" => $user->id,
                "name" => "test-" . 1,
                "email" => "test-" . 1 . "@test.com",
                "token" => $user->token
            ]
        ]);
    }
    public function test_user_login_failed()
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/users/login', [
            'email' => 'test-' . 1434 . '@test.com',
            'password' => 'test-' . 1
        ]);

        $user = User::where('name', 'test-1')->first();


        $response->assertJson([
            "message" => "Invalid credentials"
        ]);
    }
    public function test_user_login_failed_empty_form()
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/users/login', [
            'email' => 'test-' . 1434 . '@test.com',
            'password' => ''
        ]);

        $user = User::where('name', 'test-1')->first();


        $response->assertJson([
            "errors" => [
                "password" => [
                    "The password field is required."
                ]
            ]
        ]);
        $response2 = $this->post('/api/users/login', [
            'email' => '',
            'password' => 'test-1'
        ]);

        $user = User::where('name', 'test-1')->first();


        $response2->assertJson([
            "errors" => [
                "email" => [
                    "The email field is required."
                ]
            ]
        ]);
    }

    // test for update

    public function test_update_success()
    {
        $this->seed([UserSeeder::class]);

        $data = [
            'name' => "ganti",
            'email' => "ganti@ganti.com",
            'password' => 'ganti'
        ];

        $token = [
            'Authorization' => User::first()->token
        ];

        $response = $this->put('/api/users/update/1', $data, $token);

        $response->assertJson([
            "name" => "ganti",
            "email" => "ganti@ganti.com",
            "password" => "ganti"
        ]);
    }
}
