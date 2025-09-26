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
        static $pairs = [];

        return $this->getValidPair($pairs);
    }

    private function getValidPair(array &$pairs): array
    {
        $owner = User::where('role', Roles::HOUSE_OWNER)
            ->inRandomOrder()
            ->first() ?? User::factory()->create(['role' => Roles::HOUSE_OWNER]);
        $names = ['Electricity', 'Water', 'Waste', 'Service Charge', 'Gas', 'Internet'];

        do {
            $name = $this->faker->randomElement($names);
            $pair = $owner->id . '-' . $name;
        } while (in_array($pair, $pairs));

        $pairs[] = $pair;
        return [
            'house_owner_id' => $owner->id,
            'name' => $name,
        ];
    }
}
