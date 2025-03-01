<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    public $hidden = ['created_at', 'updated_at', 'book_id'];

    use HasFactory;

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
