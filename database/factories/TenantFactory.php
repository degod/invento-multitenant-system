<?php

namespace Database\Factories;

use App\Enums\Roles;
use App\Models\Flat;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'contact' => $this->faker->phoneNumber(),
            'flat_id' => Flat::inRandomOrder()->first()->id ?? Flat::factory()->create()->id,
            'house_owner_id' => User::where('role', Roles::HOUSE_OWNER)->inRandomOrder()->first()->id,
        ];
    }
}
