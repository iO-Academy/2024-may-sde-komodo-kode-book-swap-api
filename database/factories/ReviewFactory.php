<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'review' => $this->faker->paragraph(3),
            'rating'=>$this->faker->numberBetween(1, 5),
            'book_id' => $this->faker->unique()->numberBetween(1,10),
        ];
    }
}
