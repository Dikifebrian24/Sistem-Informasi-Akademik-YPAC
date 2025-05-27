<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
//        dd($row);
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
                'level' => 2,
                'is_admin' => 0,
            ]);

            $guru = Guru::create([
                'id_user'        => $id_user->id,
                'nm_guru'        => $row[0],
                'nip'            => $row[1],
                'nik'            => $row[2],
                'jenkel'         => $jenkel,
                'tmpt_lahir'     => $row[4],
                'tgl_lahir'      => $row[5],
                'almt_jalan'     => $row[6],
                'no_hp'          => $row[8],
                'agama'          => $row[9],
                'npwp'           => $row[10],
                'level_guru'          => 2
            ]);

            return $guru;
        }

        return null;
    }
}
