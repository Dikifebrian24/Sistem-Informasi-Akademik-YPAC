<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Jadwal;
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
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $id_guru = Guru::where('nm_guru', $row[0])->first()->id_guru;

        dd($id_guru);

        $jadwal = Jadwal::create([
            'id_kelainan' => $id_kelainan,
            'id_user'        => $id_user->id,
            'nm_siswa'        => $row[0],
            'nisn'            => $row[1],
            'nik'            => $row[2],
            'jenkel'         => $jenkel,
            'tmpt_lahir'     => $row[5],
            'tgl_lahir'      => $row[6],
            'almt_rumah'     => $row[7],
            'no_hp'          => $row[9],
            'agama'          => $row[10],
            'angkatan'           => $row[11],
            'nm_wali'           => $row[12],
            'no_telp_wali'           => $row[14],
            'tgl_lahir_wali'           => $row[13],
            'created_at'           => date('Y-m-d H:i:s'),
        ]);

        return 'kontol';
    }
}
