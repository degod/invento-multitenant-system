<?php

namespace Database\Seeders;

use App\Models\BillCategory;
use Illuminate\Database\Seeder;

class BillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BillCategory::factory()->count(5)->create();
    }
}