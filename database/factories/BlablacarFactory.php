<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlablacarFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 1000)
        ];
    }
}
