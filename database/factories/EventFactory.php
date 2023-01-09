<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(5, true),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(150, 4500),
            'status' => $this->faker->numberBetween(0, 1)
        ];
    }
}
