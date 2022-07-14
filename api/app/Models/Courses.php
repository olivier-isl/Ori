<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'order',
        'permalink',
        'description',
        'thumbnails',
        'created_at',
        'updated_at',
        'status',
        'title_seo',
        'meta_seo',
        'request_seo',
        'price',
        'delay',
        'json',
    ];
}
