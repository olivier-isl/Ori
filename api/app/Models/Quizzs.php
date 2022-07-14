<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzs extends Model
{
    use HasFactory;

    protected $table = 'quizzs';
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
        'json',
    ];
}
