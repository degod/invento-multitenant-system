<?php

namespace Database\Factories;

use App\Models\BillCategory;
use App\Models\User;
use App\Enums\Roles;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillCategoryFactory extends Factory
{
    protected $model = BillCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Electricity', 'Water', 'Waste', 'Service Charge', 'Gas', 'Internet']),
            'house_owner_id' => User::where('role', Roles::HOUSE_OWNER)
                                    ->inRandomOrder()
                                    ->first()
                                    ->id ?? User::factory()->create(['role' => Roles::HOUSE_OWNER])->id,
        ];
    }
}