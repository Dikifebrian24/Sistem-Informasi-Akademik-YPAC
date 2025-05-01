<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKelainan extends Model
{
    use HasFactory;

    protected $table = 'data_kelainans';

    protected $fillable = [
        'nm_kelainan',
        'desc_kelainan',
    ];
}
