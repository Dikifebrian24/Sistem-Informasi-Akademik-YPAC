<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_guru';
    protected $fillable = [
        'id_user',
        'nip',
        'nik',
        'nm_guru',
        'jenkel',
        'tmpt_lahir',
        'tgl_lahir',
        'almt_jalan',
        'no_hp',
        'agama',
        'npwp',
        'foto',
        'level_guru'
    ];
}
