<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'login',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'createrd_at',
        'updated_at',
        'description',
        'json'

    ];
}
