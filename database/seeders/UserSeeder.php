<?php

namespace Database\Seeders;

use App\Enums\Roles;
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
                'role' => Roles::ADMIN,
            ]);

            // artisan command to log the super admin credentials
            $this->command->info('=================================');
            $this->command->info('Super Admin Created!');
            $this->command->info('=================================');
            $this->command->info('EMAIL:    admin@invento.test');
            $this->command->info('PASSWORD: admin123');
            $this->command->info('=================================');
        }

        // Sample House Owners
        User::factory()->count(5)->create([
            'role' => Roles::HOUSE_OWNER,
        ]);

        $this->command->info('=================================');
        $this->command->info('5 House Owners Created!');
        $this->command->info('=================================');
        $this->command->info('PASSWORD: password');
        $this->command->info('=================================');
    }
}
