<?php

namespace Database\Seeders;

use App\Models\BillCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(BuildingSeeder::class);
        $this->call(TenantSeeder::class);
        $this->call(FlatSeeder::class);
        $this->call(BillCategorySeeder::class);
        $this->call(BillSeeder::class);
    }
}