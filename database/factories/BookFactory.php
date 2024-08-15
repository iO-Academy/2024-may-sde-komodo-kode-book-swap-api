<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(rand(1,5),true),
            'author' => $this->faker->word(),
            'blurb' => $this->faker->paragraph(2),
            'image' => $this->faker->imageUrl(600,840),
            'page_count' => rand(100,500),
            'genre_id' => Genre::factory(),
            'reviews_id' => Review::factory(),
            'year' => $this->faker->year(),
        ];
    }
}
