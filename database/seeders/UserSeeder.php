<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'login' => 'admin',
            'password' => Hash::make('admin'),
            'position_id' => 0,
        ]);

        User::create([
            'name' => 'user',
            'login' => 'user',
            'password' => Hash::make('user'),
            'position_id' => 1,
        ]);
        User::factory()->count(5)->create();
    }
}
