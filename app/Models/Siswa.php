<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_user',        // Menambahkan id_user ke dalam fillable
        'id_kelainan',
        'nm_siswa',
        'nik',
        'nisn',
        'jenkel',
        'tmpt_lahir',
        'tgl_lahir',
        'agama',
        'almt_rumah',
        'no_hp',
        'angkatan',
        'kd_kelas',
        'nm_wali',
        'tgl_lahir_wali',
        'no_telp_wali',
    ];
}
