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
    public function test_getSingleBook_success(): void
    {
        Book::factory()->count(10)->create();
        $response = $this->get('/api/books/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                ->has('data',function (AssertableJson $json){
                    $json->hasAll([
                        'id',
                        'title',
                        'author',
                        'blurb',
                        'claimed_by_name',
                        'image',
                        'page_count',
                        'year',
                        'genre',
                        'reviews'
                    ])
                    ->has('genre',function (AssertableJson $json){
                        $json->hasAll(['id','name']);
                    })
                    ->has('reviews',1,function (AssertableJson $json){
                        $json->hasAll(['id','name','review','rating']);
                    });

                });
            });

    }

    public function test_getSingleBook_fail(): void
    {
        $response = $this->get("/api/books/3421789321709831");

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
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

    public function test_getAllBooks_genre(): void
    {
        Book::factory()->count(10)->create();

        $response = $this->get('/api/books?genre');

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