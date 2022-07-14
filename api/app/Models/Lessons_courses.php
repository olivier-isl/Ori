<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons_courses extends Model
{
    use HasFactory;

    protected $table = 'lessons_courses';
    public $timestamps = false;
    protected $fillable = [
        'courses_id',
        'lessons_id',
        'quizz_id',
        'order'
    ];
}
