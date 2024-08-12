<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();
        for($i=0; $i<10; $i++){
            DB::table('books')->insert([
                'title'=>$faker->words(rand(1,5)),
                'author'=>$faker->name(),
                'blurb'=>$faker->paragraph(3),
                'claimed_by_name'=>NULL,
                'image'=>$faker->imageUrl(),
                'page_count'=>rand(100,500),
                'genre_id'=>rand(1,10),
                'reviews_id'=>$faker->unique()->numberBetween(1,10)
            ]);
        }
    }
}
