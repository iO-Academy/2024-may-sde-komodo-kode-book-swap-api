<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for($i=0; $i<10; $i++){
            DB::table('reviews')->insert([
                'name'=>$faker->name(),
                'review'=>$faker->paragraph(3),
                'book_id'=>$faker->unique()->numberBetween(1,10),
            ]);}
    }
}
