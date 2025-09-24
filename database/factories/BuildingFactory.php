<?php

namespace Database\Factories;

use App\Enums\Roles;
use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Building>
 */
class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Apartments',
            'address' => $this->faker->address(),
            'house_owner_id' => User::where('role', Roles::HOUSE_OWNER)->inRandomOrder()->first()->id,
        ];
    }
}
