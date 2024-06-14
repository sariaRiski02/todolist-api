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
        $todo = Todo::first();
        $user = $todo->user;
        $this->assertEquals($todo->id_user, $user->id);
    }
}
