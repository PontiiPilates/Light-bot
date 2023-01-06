<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TimetableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'entity_id' => $this->faker->numberBetween(1, 10),
            'day' => $this->faker->randomElement(['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']),
            'day_number' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7]),
            'date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'time' => $this->faker->time('H:i:s'),
        ];
    }
}
