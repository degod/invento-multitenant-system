<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Flat;
use App\Models\User;
use App\Enums\Roles;
use App\Models\BillCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $flat = Flat::inRandomOrder()->first() ?? Flat::factory()->create();
        $ownerId = $flat->house_owner_id;

        return [
            'month' => $this->faker->monthName . ' ' . $this->faker->year,
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            'notes' => $this->faker->optional()->sentence(),
            'bill_category_id' => BillCategory::inRandomOrder()->first()->id ?? BillCategory::factory()->create()->id,
            'flat_id' => $flat->id,
            'house_owner_id' => $ownerId,
        ];
    }
}