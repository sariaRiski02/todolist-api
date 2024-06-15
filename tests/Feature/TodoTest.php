<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\TodoSeeder;
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
            '/api/todos',
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
            '/api/todos',
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
            '/api/todos',
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



    public function test_getAllTodoByUser()
    {
        $this->seed([UserSeeder::class]);
        $this->seed([TodoSeeder::class]);


        $response = $this->get('/api/todos', [
            'Authorization' => User::first()->token
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            ["data" => User::first()->todos->toArray()]
        );
    }


    public function test_getAllTodoByUserFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->seed([TodoSeeder::class]);

        $response = $this->get('/api/todos', [
            "Authorization" => "salah"
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "errors" => [
                "message" => "Unauthorized"
            ]
        ]);
    }


    public function test_getTodoByIdSuccess()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo = $user->todos->first();

        $response = $this->get("/api/todos/$todo->id", [
            "Authorization" => $user->token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            "data" => $todo->toArray()
        ]);
    }


    public function test_getTodoByIdFailedIdNotFound()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $response = $this->get("/api/todos/salah", [
            "Authorization" => $user->token
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            "errors" => [
                "message" => "Todo not found"
            ]
        ]);
    }


    public function test_updateTodoSuccess()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::first();
        $todo = $user->todos->first();


        $response = $this->put(
            "/api/todos/$todo->id",
            [
                "title" => "title changed",
                "description" => "description changed",
                "completed" => true
            ],
            [
                "Authorization" => $user->token
            ]
        );
        $todo = $user->todos->first();
        $response->assertJson([
            "data" => $todo->toArray()
        ]);
    }


    public function test_deleteTodoSuccess()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo_id = $user->todos->first()->id;
        $response = $this->delete(uri: "/api/todos/$todo_id", headers: [
            "Authorization" => $user->token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('todos', ['id' => $todo_id]);
        $response->assertExactJson([1]); // expected true = 1

    }

    public function test_deleteTodoFailedUnauthorized()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo_id = $user->todos->first()->id;
        $response = $this->delete(uri: "/api/todos/$todo_id", headers: [
            "Authorization" => "salah"
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "errors" => [
                "message" => "Unauthorized"
            ]
        ]);
    }

    public function test_deleteTodoFailed()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $response = $this->delete(uri: "/api/todos/Id_wrong", headers: [
            "Authorization" => $user->token
        ]);

        $response->assertjson([
            "errors" => [
                "message" => "Todo not found"
            ]
        ]);
    }

    public function test_SearchSuccess()
    {
        $this->seed([userSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo = $user->todos->first();
        // dd($todo->title);
        $response = $this->post(
            "/api/todos/search",
            [
                "search" => $todo->title
            ],
            [
                "Authorization" => $user->token
            ]
        );

        $response->assertJson([
            "data" => [
                $todo->toArray()
            ]
        ]);
    }

    public function testSearchFailed()
    {
        $this->seed([userSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo = $user->todos->first();
        // dd($todo->title);
        $response = $this->post(
            "/api/todos/search",
            [
                "search" => "empty"
            ],
            [
                "Authorization" => $user->token
            ]
        );

        $response->assertJson([
            "errors" => [
                "message" => "Todo not found"
            ]
        ]);
    }
}
