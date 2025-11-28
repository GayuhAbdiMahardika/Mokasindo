<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    // Helper untuk mencari by slug
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }
}

// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Page extends Model
// {
//     use HasFactory;

//     protected $guarded = ['id'];

//     // Helper untuk mencari by slug
//     public static function findBySlug($slug)
//     {
//         return self::where('slug', $slug)->firstOrFail();
//     }
// }