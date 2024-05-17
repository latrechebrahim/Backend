<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'specialty',
        'email',
        'phonenumber',
        'date_birth',
        'path',
        'admin',
        'password',
        'confirmpassword',
        'isAvailable'

    ];

    protected $hidden = [
        'password',
        'confirmpassword',
        'remember_token',
    ];
}
