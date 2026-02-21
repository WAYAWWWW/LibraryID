<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'pages',
        'category',
        'description',
        'cover_image',
        'synopsis',
        'reads_count',
        'stock',
        'year',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
