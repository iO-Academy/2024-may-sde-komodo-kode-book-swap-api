<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
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
        $response = $this->getJson('/api/books/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', function (AssertableJson $json) {
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
                            ->has('genre', function (AssertableJson $json) {
                                $json->hasAll(['id', 'name']);
                            })
                            ->has('reviews',1, function (AssertableJson $json) {
                                $json->hasAll(['id', 'name', 'review', 'rating']);
                            });

                    });
            });

    }

    public function test_getSingleBook_fail(): void
    {
        $response = $this->getJson("/api/books/3421789321709831");

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
    }

    public function test_getAllBooks_success(): void
    {
        Book::factory()->count(10)->create();

        $response = $this->getJson('/api/books');

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
            });
    }

    public function test_getAllBooks_genre(): void
    {
        Book::factory()->count(1)->create();

        $response = $this->getJson('/api/books?genre=1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 1, function (AssertableJson $json) {
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
            });
    }

    public function test_getAllBooks_genre_error(): void
    {
        Book::factory()->count(10)->create();

        $response = $this->getJson('/api/books?genre=77');

        $response->assertStatus(422)
            ->assertInvalid(['genre']);
    }

    public function test_getAllBooks_search(): void
    {
        Book::factory()->create(['title' => 'test', 'author' => 'test', 'blurb' => 'test']);
        Book::factory()->create(['title' => 'enim']);

        $response = $this->getJson('/api/books?search=enim');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 1, function (AssertableJson $json) {
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
            });
    }

    public function test_getAllBooks_searchEmpty(): void
    {
        Book::factory()->count(4)->create();

        $response = $this->getJson('/api/books?search=enimuhiodghoifgndfiongmtestgf');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 0);
            });
    }

    public function test_getAllBooks_search_failed(): void
    {
        Book::factory()->count(10)->create();
        $data = null;
        $response = $this->getJson("/api/books?search={$data}");

        $response->assertStatus(422)
            ->assertInvalid(['search']);
    }

    public function test_getBooksByClaimed_validation():void
    {
        $data = "Will man";
        $response = $this->getJson("/api/books?claimed={$data}");
        $response->assertInvalid(['claimed']);
    }

    public function test_getBooksByClaimed_success(): void
    {
        Book::factory()->count(3)->create();
        Book::factory()->count(7)->create(['claimed_by_name' => 'george', 'email' => "george@hotmail.com"]);

        $data = 1;

        $response = $this->getJson("/api/books?claimed={$data}");

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

        $response = $this->getJson("/api/books?claimed={$data}");

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
            });
    }

    public function test_claimBook_success(): void
    {
        $data = [
            "name" => "milan",
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create();

        $response = $this->putJson('/api/books/claim/1', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', ["claimed_by_name" => "milan", "email" => "milan@hotmail.com"]);
    }

    public function test_claimBook_alreadyClaimed(): void
    {
        $data = [
            "name" => "milan",
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create(['claimed_by_name' => 'george', 'email' => "george@hotmail.com"]);

        $response = $this->putJson('/api/books/claim/1', $data);

        $response->assertStatus(400);
    }

    public function test_claimBook_validation(): void
    {
        $data = [
            "name" => null,
            "email" => "milom"
        ];

        $response = $this->putJson('/api/books/claim/1', $data);

        $response->assertInvalid(['name', 'email'])->assertStatus(422);
    }

    public function test_unclaimBook_success()
    {
        $data = [
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create(['claimed_by_name' => 'milan', 'email' => "milan@hotmail.com" ]);

        $response = $this->putJson('/api/books/return/1', $data);

        $response->assertStatus(200);

        $emptyData = ['claimed_by_name' => null, 'email' => null];
        $this->assertDatabaseHas('books', $emptyData);
    }

    public function test_unclaimBook_bookAlreadyUnclaimed()
    {
        $data = [
            "email" => "milan@hotmail.com"
        ];

        Book::factory()->create(['claimed_by_name' => null, 'email' => null]);

        $response = $this->putJson('/api/books/return/1', $data);

        $response->assertStatus(400);
    }

    public function test_unclaimBook_wrongEmail()
    {
        $data = [
            "email" => "will@gmail.com"
        ];

        Book::factory()->create(['claimed_by_name' => 'milan', 'email' => "milan@hotmail.com" ]);

        $response = $this->putJson('/api/books/return/1', $data);

        $response->assertStatus(400);
    }

    public function test_unclaimBook_validation(): void
    {
        $data = [
            "email" => "spaghetti"
        ];

        $response = $this->putJson('/api/books/return/1', $data);

        $response->assertInvalid(['email'])->assertStatus(422);
    }
       
    public function test_addBookSuccess():void
    {
        Genre::factory()->create();
        $testData = [
            'title'=>'z',
            'author'=>'znarf',
            'genre_id'=>1,
            'blurb'=>'good book',
            'image'=>'url',
            'year'=>2023,
            ];
        $response = $this->postJson('/api/books', $testData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $testData);
    }

    public function test_addBookFail():void
    {
        $testData = [
            'title'=>'',
            'author'=>67,
            'genre_id'=>5,
            'blurb'=>'good book',
            'image'=>'url',
            'year'=>2023,
        ];
        $response = $this->postJson('/api/books', $testData);
        $response->assertStatus(422)
            ->assertInvalid(['title']);
    }

    public function test_addBookOptional():void
    {
        Genre::factory()->create();
        $testData = [
            'title'=>'hello',
            'author'=>'author',
            'genre_id'=>1,
        ];
        $response = $this->postJson('/api/books', $testData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $testData);
    }
}