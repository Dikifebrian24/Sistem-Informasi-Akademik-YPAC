<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'id_siswa',
        'id_mapel',
        'kategori_nilai',
        'nilai',
        'desc_nilai',
        'lampiran',
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    // Relasi ke model Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }
}
