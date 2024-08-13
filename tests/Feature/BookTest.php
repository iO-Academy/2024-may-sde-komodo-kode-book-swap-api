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

    public function test_getBooksByClaimed_validation():void
    {
        $data = "Will man";
        $response = $this->get("/api/books?claimed={$data}");
        $response->assertInvalid(['claimed']);
    }

    public function test_getBooksByClaimed_success(): void
    {
        Book::factory()->count(3)->create();
        Book::factory()->count(7)->create(['claimed_by_name' => 'george', 'email' => "george@hotmail.com"]);

        $data = 1;

        $response = $this->get("/api/books?claimed={$data}");

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 7, function (AssertableJson $json) {
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

    public function test_getBooksByUnclaimed_success()
    {
        Book::factory()->count(3)->create();
        Book::factory()->count(7)->create(['claimed_by_name' => 'george', 'email' => "george@hotmail.com"]);

        $data = 0;

        $response = $this->get("/api/books?claimed={$data}");

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 3, function (AssertableJson $json) {
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

    public function test_claimBook_success(): void
    {
        $data = [
            "claimed_by_name" => "milan",
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create();

        $response = $this->putJson('/api/books/1', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', $data);
    }

    public function test_claimBook_alreadyClaimed(): void
    {
        $data = [
            "claimed_by_name" => "milan",
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create(['claimed_by_name' => 'george', 'email' => "george@hotmail.com"]);

        $response = $this->putJson('/api/books/1', $data);

        $response->assertStatus(400);
    }

    public function test_claimBook_validation(): void
    {
        $data = [
            "claimed_by_name" => null,
            "email" => "milom"
        ];

        $response = $this->putJson('/api/books/1', $data);

        $response->assertInvalid(['claimed_by_name', 'email'])->assertStatus(422);
    }

}