<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressNilai extends Model
{
    protected $table = 'progress_nilais';

    protected $fillable = [
        'id_siswa',
        'id_mapel',
        'tgl_progress',
        'nilai',
        'desc_nilai',
        'lampiran',
    ];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id');
        // sesuaikan nama foreign key jika beda
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
        // sesuaikan nama foreign key jika beda
    }
}
