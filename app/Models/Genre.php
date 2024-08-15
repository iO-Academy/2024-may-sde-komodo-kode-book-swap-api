<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    public $hidden = ['created_at', 'updated_at'];

    use HasFactory;

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
