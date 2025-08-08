<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Illuminate\Database\Seeder;

class JobPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPosition::create([
            'id' => 0,
            'name' => 'Администратор',
        ]);

        $positions = [
            ['name' => 'CEO'],
            ['name' => 'CTO'],
            ['name' => 'Team Lead'],
            ['name' => 'Senior Developer'],
            ['name' => 'Middle Developer'],
            ['name' => 'Junior Developer'],
            ['name' => 'DevOps Engineer'],
        ];

        JobPosition::insert($positions);
    }
}
