<?php

namespace Tests\Feature;

use App\Models\Todo;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_relationship()
    {
        $user = User::first();
        $todos_from_first_user = $user->todos;
        $todo = Todo::first();

        $this->assertEquals($todos_from_first_user->first()->id, $todo->id);
    }
}
