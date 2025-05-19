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
}
