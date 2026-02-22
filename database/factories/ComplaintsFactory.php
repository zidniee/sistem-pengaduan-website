<?php

namespace Database\Factories;

use App\Models\Complaints;
use App\Models\Platforms;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaints>
 */
class ComplaintsFactory extends Factory
{
    protected $model = Complaints::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'username' => fake()->userName(),
            'platform_id' => Platforms::factory(),
            'description' => fake()->sentence(),
            'account_url' => fake()->unique()->url(),
            'submitted_at' => fake()->date(),
            'ticket' => fake()->optional()->bothify('TKT-###'),
            'bukti' => fake()->optional()->imageUrl(),
        ];
    }
}
