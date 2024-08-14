<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('blurb')->nullable();
            $table->string('claimed_by_name')->nullable();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->integer('page_count')->nullable();
            $table->integer('year')->nullable();
            $table->foreignId('genre_id');
            $table->foreignId('reviews_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
