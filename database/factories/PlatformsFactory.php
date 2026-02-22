<?php

namespace Database\Factories;

use App\Models\Platforms;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformsFactory extends Factory
{
    protected $model = Platforms::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Facebook', 'Instagram', 'Twitter', 'TikTok', 'YouTube']),
            'url' => fake()->url(),
        ];
    }
}
