<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'title'       => fake()->sentence(4),
            'category'    => fake()->randomElement(Ad::CATEGORIES),
            'description' => fake()->paragraph(3),
            'price'       => fake()->numberBetween(1000, 500000),
            'location'    => fake()->randomElement(['Abidjan', 'Yamoussoukro', 'Bouaké', 'Daloa', 'San-Pédro', 'Korhogo']),
            'condition'   => fake()->randomElement(['new', 'good', 'used']),
            'photo'       => null,
        ];
    }
}
