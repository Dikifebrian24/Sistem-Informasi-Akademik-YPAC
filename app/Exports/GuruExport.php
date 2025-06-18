<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $guru = DB::table('gurus')
            ->select('nm_guru', 'nip', 'nik', 'jenkel', 'tmpt_lahir', 'tgl_lahir', 'almt_jalan', 'users.email', 'no_hp', 'agama', 'npwp')
            ->join('users', 'users.id', '=', 'gurus.id_user')
            ->get();

        return $guru;
    }

    public function headings(): array
    {
        return [
            'Nama Guru',
            'NIP',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Email',
            'No HP',
            'Agama',
            'NPWP'
        ];
    }

    public function map($row): array
    {
        return [
            (string)$row->nm_guru,
            (string)$row->nik,
            (string)$row->nik,
            (string)$row->jenkel,
            (string)$row->tmpt_lahir,
            (string)$row->tgl_lahir,
            (string)$row->almt_jalan,
            (string)$row->email,
            (string)$row->no_hp,
            (string)$row->agama,
            (string)$row->npwp
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
        ];
    }
}
