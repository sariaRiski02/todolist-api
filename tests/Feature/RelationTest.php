<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_relationship()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::first();
        $todo = $user->todos->first();


        // dd($todo);

        if ($todo) {
            $todo_validated = Todo::where("id_user", $user->id)->first();

            if ($todo_validated) {
                $this->assertSame($todo->title, $todo_validated->title);
            } else {
                $this->assertTrue(false);
            }
        } else {
            $this->assertTrue(false);
        }
    }
}
