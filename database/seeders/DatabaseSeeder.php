<?php

namespace Database\Seeders;

use App\Models\Todo;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(3)->create();

        $user = User::first();
        Todo::create([
            'id' => Str::uuid(),
            'title' => 'First todo',
            'description' => 'First todo description',
            'completed' => false,
            'id_user' => $user->id
        ]);
    }
}
