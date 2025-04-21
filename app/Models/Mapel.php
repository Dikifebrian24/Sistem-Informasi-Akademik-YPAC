<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nm_mapel',
        'id_kelas',
        'created_at',
        'updated_at'
    ];
}
