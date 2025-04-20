<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillabel   = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'is_admin',
        'level',
        'created_at',
        'updated_at'
    ];
}
