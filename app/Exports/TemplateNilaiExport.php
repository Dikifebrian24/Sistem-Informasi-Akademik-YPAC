<?php
namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateNilaiExport implements FromArray, WithHeadings
{
    protected $id_kelas;

    public function __construct($id_kelas, $id_mapel)
    {
        $this->id_kelas = $id_kelas;
        $this->id_mapel = $id_mapel;
    }

    public function headings(): array
    {
        $pertemuan = DB::table('jadwals')
            ->where('id_kelas', '=', $this->id_kelas)
            ->where('id_mapel', '=', $this->id_mapel)
            ->pluck('materi') // ambil kolom materi saja, jadi hasilnya array string
            ->toArray();

        return array_merge(['Nama Siswa', 'NISN'], $pertemuan);
    }


    public function array(): array
    {
//        $siswa = Kelas::find($this->id_kelas)->siswa;

        $siswa = DB::table('kelas_siswa')
            ->join('siswas', 'siswas.id_siswa', '=', 'kelas_siswa.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_siswa.id_kelas')
            ->where('kelas_siswa.id_kelas', $this->id_kelas)
            ->get();

        return $siswa->map(function ($s) {
            return array_merge([$s->nm_siswa, $s->nisn], []);
        })->toArray();
    }
}
