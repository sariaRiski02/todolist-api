<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_createTodoSuccess(): void
    {
        $this->seed([UserSeeder::class]);
        $user = User::first();
        $response = $this->post(
            '/api/todos/create',
            [
                'title' => 'Test Title',
                'description' => 'Test Description'
            ],
            [
                'Authorization' => $user->token
            ]
        );
        // $response->assertStatus(200);
        $response->assertJson([
            "data" => [
                'id' => $user->todos->first()->id,
                'title' => 'Test Title',
                'description' => 'Test Description',
                'completed' => false,
                'id_user' => $user->id
            ]
        ]);
    }

    public function test_createTodoFailed()
    {
        $this->seed([UserSeeder::class]);
        $user = User::first();
        $response = $this->post(
            '/api/todos/create',
            [
                'title' => 'Test Title',
                'description' => 'Test Description'
            ],
            [
                'Authorization' => "salah"
            ]
        );
        // $response->assertStatus(200);
        $response->assertJson([
            "errors" => [
                "message" => "Unauthorized"
            ]
        ]);
    }
    public function test_createTodoFailedMissingFildName()
    {
        $this->seed([UserSeeder::class]);
        $user = User::first();
        $response = $this->post(
            '/api/todos/create',
            [
                'title' => '',
                'description' => 'Test Description'
            ],
            [
                'Authorization' => $user->token
            ]
        );
        $response->assertStatus(400);
        $response->assertJson([
            "errors" => [

                "title" => [
                    "The title field is required."

                ]
            ]
        ]);
    }
}
