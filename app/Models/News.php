<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// ... di dalam file app/Models/News.php

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author_id', // Pastikan ini ada
        'title',
        'slug',
        'subtitle',
        'content',
        'featured_image_url',
        'is_published',
        'publication_date',
    ];

    // ... sisa kode model ...
}
