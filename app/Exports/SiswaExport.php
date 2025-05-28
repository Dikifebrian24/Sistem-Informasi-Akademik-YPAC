<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $siswa = DB::table('siswas')
            ->select('nm_siswa', 'nisn', 'nik', 'jenkel', 'tmpt_lahir', 'tgl_lahir', 'agama', 'data_kelainans.nm_kelainan','almt_rumah', 'no_hp', 'angkatan', 'nm_wali', 'no_telp_wali')
            ->join('data_kelainans', 'data_kelainans.id', '=', 'siswas.id_kelainan')
            ->join('users', 'users.id', '=', 'siswas.id_user')
            ->get();

        return $siswa;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NISN',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kelainan',
            'Alamat Rumah',
            'No HP Siswa',
            'Angkatan',
            'Nama Wali',
            'No Telepon Wali'
        ];
    }

    public function map($row): array
    {
        return [
            (string) $row->nm_siswa,
            (string) $row->nisn,
            (string) $row->nik,
            (string) $row->jenkel,
            (string) $row->tmpt_lahir,
            (string) $row->tgl_lahir,
            (string) $row->agama,
            (string) $row->nm_kelainan,
            (string) $row->almt_rumah,
            (string) $row->no_hp,
            (string) $row->angkatan,
            (string) $row->nm_wali,
            (string) $row->no_telp_wali,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
