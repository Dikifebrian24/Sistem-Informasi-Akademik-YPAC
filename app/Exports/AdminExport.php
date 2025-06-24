<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AdminExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $guru = DB::table('users')
            ->select('first_name', 'last_name','email')
            ->where('level', '=', 1)
            ->get();

        return $guru;
    }

    public function headings(): array
    {
        return [
            'Nama Admin',
            'Email'
        ];
    }

    public function map($row): array
    {
        return [
            (string)$row->first_name . ' ' . $row->last_name,
            (string)$row->email,

        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,

        ];
    }
}
