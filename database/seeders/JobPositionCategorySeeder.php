<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JobPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPositionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $jobPositions = JobPosition::all();
        $data = [];

        foreach ($categories as $category) {
            foreach ($jobPositions as $jobPosition) {
                $data[] = [
                    'category_id' => $category->id,
                    'job_position_id' => $jobPosition->id,
                ];
            }
        }
        DB::table('job_position_category')->insert($data);
    }
}
