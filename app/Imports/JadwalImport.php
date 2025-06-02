<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\DataKelainan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JadwalImport implements ToModel, WithStartRow
{

    protected $id_kelas;
    public function __construct($id_kelas)
    {
        $this->id_kelas = $id_kelas;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $id_guru = Guru::where('nm_guru', $row[0])->first()->id_guru;
        $id_mapel = Mapel::where('nm_mapel', $row[1])->first()->id;

//        dd($id_guru, $this->id_kelas);
        $jadwal = Jadwal::create([
            'id_kelas'      => $this->id_kelas,
            'id_guru'       => $id_guru,
            'id_mapel'      => $id_mapel,
            'materi'        => $row[2],
            'waktu_mulai'   => $row[4],
            'waktu_selesai' => $row[5],
            'tanggal'       => $row[3],
        ]);

        return $jadwal;
    }
}
