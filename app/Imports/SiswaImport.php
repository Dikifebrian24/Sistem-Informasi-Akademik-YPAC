<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\DataKelainan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $id_kelainan = DataKelainan::where('nm_kelainan', 'like', '%' . trim($row[4]) . '%')->first()->id;

//        dd($id_kelainan);


        if ($row[3] == 'L') {
            $jenkel = 'Laki - Laki';
        } else {
            $jenkel = 'Perempuan';
        }

        if (!empty($row[7]) && !User::where('email', $row[7])->exists()) {
            $fullName = $row[0];
            $parts = explode(' ', $fullName);

            $firstName = array_shift($parts);
            $lastName = implode(' ', $parts);

            $id_user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $row[7],
                'password' => Hash::make( $row[1]),
                'remember_token' => Str::random(10),
                'is_active' => 1,
                'level' => 3,
                'is_admin' => 0,
            ]);

            $siswa = Siswa::create([
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

            return $siswa;
        }

        return null;
    }
}
