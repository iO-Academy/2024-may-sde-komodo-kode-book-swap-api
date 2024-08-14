<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class ReviewTest extends TestCase
{
    use DatabaseMigrations;

    public function test_createReview_validation(): void
    {
        $data = [
            'name' => 6,
            'rating' => 46,
            'review' => false
        ];

        $response = $this->postJson('/api/reviews', $data);

        $response->assertInvalid(['name', 'rating', 'review', 'book_id'])->assertStatus(422);
    }

    public function test_createReview_success(): void
    {
        $data = [
            'name' => 'Cuthbert',
            'rating' => 4,
            'review' => 'Not bad, the ending was pretty dope.',
            'book_id' => 2
        ];

        Book::factory()->count(2)->create();

        $response = $this->postJson('/api/reviews', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', $data);
    }
}