<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class NilaiImport implements ToCollection
{
    protected $id_kelas;
    protected $id_mapel;

    public function __construct($id_kelas, $id_mapel)
    {
        $this->id_kelas = $id_kelas;
        $this->id_mapel = $id_mapel;
    }

    public function collection(Collection $rows)
    {
        $header = $rows->first();

        $materiHeaders = [];
        foreach ($header as $index => $val) {
            if ($index < 2) continue;
            $materiHeaders[] = trim($val);
        }

        $jadwals = DB::table('jadwals')
            ->where('id_kelas', $this->id_kelas)
            ->where('id_mapel', $this->id_mapel)
            ->get()
            ->keyBy(function ($item) {
                return trim($item->materi);
            });

        foreach ($rows->skip(1) as $row) {
            $nisn = $row[1];
            $siswa = Siswa::where('nisn', $nisn)->first();

            if (!$siswa) {
                continue;
            }

            foreach ($materiHeaders as $idx => $materi) {
                $nilai = $row[$idx + 2];

                if ($nilai === null || $nilai === '') {
                    continue;
                }

//                dd($jadwals[$materi]);
                if (!isset($jadwals[$materi])) {
                    continue;
                }

                $id_jadwal = $jadwals[$materi]->id;
//                dd($id_jadwal);

                Nilai::updateOrCreate(
                    [
                        'id_siswa' => $siswa->id_siswa,
                        'id_mapel' => $this->id_mapel,
                        'id_jadwal' => $id_jadwal,
                        'kategori_nilai' => $materi,
                    ],
                    [
                        'nilai' => (int)$nilai,
                        'desc_nilai' => null,
                        'lampiran' => null,
                    ]
                );
            }
        }
    }
}
