<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {

            User::create([
                'name' => 'test-' . $i,
                'email' => 'test-' . $i . '@test.com',
                'password' => Hash::make('test-' . $i),
                "token" => "test-token-$i"
            ]);
        }
    }
}
