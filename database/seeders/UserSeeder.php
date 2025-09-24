<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin or Not
        if (User::where('email', 'admin@invento.test')->doesntExist()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@invento.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'house_owner_id' => null,
            ]);
        }

        // Sample House Owners
        User::factory()->count(5)->create([
            'role' => 'house-owner',
        ]);
    }
}
