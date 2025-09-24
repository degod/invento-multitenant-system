<?php

namespace Database\Factories;

use App\Enums\Roles;
use App\Models\Flat;
use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flat>
 */
class FlatFactory extends Factory
{
    protected $model = Flat::class;

    public function definition(): array
    {
        return [
            'flat_number' => strtoupper($this->faker->bothify('??-#')),
            'owner_name' => $this->faker->name(),
            'building_id' => Building::inRandomOrder()->first()->id ?? Building::factory()->create()->id,
            'house_owner_id' => User::where('role', Roles::HOUSE_OWNER)->inRandomOrder()->first()->id,
        ];
    }
}
