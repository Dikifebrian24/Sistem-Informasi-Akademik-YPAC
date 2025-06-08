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

    // Terima id_kelas dan id_mapel untuk filter jadwal
    public function __construct($id_kelas, $id_mapel)
    {
        $this->id_kelas = $id_kelas;
        $this->id_mapel = $id_mapel;
    }

    public function collection(Collection $rows)
    {
        // Ambil header (baris pertama)
        $header = $rows->first();

        // Materi dari header mulai index 2 ke atas (skip NAMA dan NISN)
        $materiHeaders = [];
        foreach ($header as $index => $val) {
            if ($index < 2) continue; // skip kolom NAMA dan NISN
            $materiHeaders[] = trim($val);
        }

        // Ambil jadwals untuk mapel dan kelas yang relevan, key by materi exact case (bisa lower)
        $jadwals = DB::table('jadwals')
            ->where('id_kelas', $this->id_kelas)
            ->where('id_mapel', $this->id_mapel)
            ->get()
            ->keyBy(function ($item) {
                return trim($item->materi); // tanpa strtolower, pakai exact match
            });

        // Loop data mulai dari baris ke-2 (skip header)
        foreach ($rows->skip(1) as $row) {
            // Ambil nisn dari kolom index 1
            $nisn = $row[1];
            $siswa = Siswa::where('nisn', $nisn)->first();

//            dd($siswa);
            if (!$siswa) {
                // Siswa tidak ditemukan, skip baris ini
                continue;
            }

            // Loop materiHeaders, mulai dari kolom index 2 dan seterusnya
            foreach ($materiHeaders as $idx => $materi) {
                // Kolom Excel index-nya adalah idx + 2 (karena materiHeaders mulai dari index 0 untuk kolom 2)
                $nilai = $row[$idx + 2];
//                dd($nilai);

                if ($nilai === null || $nilai === '') {
                    continue; // skip nilai kosong
                }

//                dd($jadwals[$materi]);
                // Cek jadwal materi ada
                if (!isset($jadwals[$materi])) {
                    // Materi tidak ditemukan di jadwals, skip
                    continue;
                }

                $id_jadwal = $jadwals[$materi]->id;
//                dd($id_jadwal);

                // Simpan nilai update or create
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
