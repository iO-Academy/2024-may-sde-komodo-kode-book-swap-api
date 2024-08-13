<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class BookTest extends TestCase
{
    use DatabaseMigrations;

    /**
     */
    public function test_getSingleBook_success(): void
    {
        Book::factory()->create();
        $response = $this->get('/api/books/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                ->has('data',12);
            });

    }

    public function test_getSingleBook_fail(): void
    {
        Book::factory()->create();
        $response = $this->get("/api/books/3421789321709831");

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });
    }
    public function test_getAllBooks_success(): void
    {
        Book::factory()->count(10)->create();

        $response = $this->get('/api/books');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 10, function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'title',
                            'author',
                            'image',
                            'genre'
                        ])->has('genre', function (AssertableJson $json) {
                            $json->hasAll(['id', 'name']);
                        });
                });
            })
        ;
    }
}