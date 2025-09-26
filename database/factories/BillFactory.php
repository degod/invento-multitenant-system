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
        static $pairs = [];

        $initialArr = [
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            'notes' => $this->faker->optional()->sentence(),
        ];
        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        do {
            $month = $this->faker->monthName . ' ' . $this->faker->year;
            $flat = Flat::inRandomOrder()->first() ?? Flat::factory()->create();
            $ownerId = $flat->house_owner_id;
            $billCategory = BillCategory::where('house_owner_id', $ownerId)->inRandomOrder()->first() ?? BillCategory::factory()->create(['house_owner_id' => $ownerId]);

            $pair = $flat->id . '-' . $month;
        } while (in_array($pair, $pairs));

        $pairs[] = $pair;
        return [
            'flat_id' => $flat->id,
            'month' => $month,
            'house_owner_id' => $ownerId,
            'bill_category_id' => $billCategory->id,
        ];
    }
}
