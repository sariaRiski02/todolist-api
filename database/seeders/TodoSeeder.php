<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 1; $i <= 5; $i++) {
            Todo::create([
                'id' => Str::uuid(),
                'title' => 'todo ke' . $i,
                'description' => 'First todo description' . $i,
                'completed' => false,
                'id_user' => User::inRandomOrder()->first()->id
            ]);
        }
    }
}
