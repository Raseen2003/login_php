<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable   
{
     use hasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phoneno',
        'address',
        'profile_pic',
        'is_deleted',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}