<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_kelas';
    protected $fillable = [
        'id_kelas',
        'id_mapel',
        'waktu_mulai',
        'waktu_selesai',
        'tanggal',
    ];

}
