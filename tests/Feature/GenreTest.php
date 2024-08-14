<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     */
    public function test_getAllGenres_success(): void
    {
        Genre::factory()->count(10)->create();

        $response = $this->getJson('/api/genres');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['data', 'message'])
                    ->has('data', 10, function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'name'
                        ]);
                    });
            });
    }
}
