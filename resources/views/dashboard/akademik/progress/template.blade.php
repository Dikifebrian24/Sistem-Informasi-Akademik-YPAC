<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Laporan Nilai Siswa: {{ $siswa->nm_siswa }}</h2>

@php
    $nilai = $nilai->sortBy('nm_mapel');
    $grouped = $nilai->groupBy('id_mapel');
    $no = 1;
@endphp

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Mata Pelajaran</th>
        <th>Materi</th>
        <th>Nilai</th>
        <th>Rata-Rata Mapel</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grouped as $id_mapel => $mapelItems)
        @php
            $avg = round($mapelItems->avg('nilai'), 2);
            $rowspan = $mapelItems->count();
            $firstRow = true;
            $pertemuan = 1;
        @endphp

        @foreach($mapelItems as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nm_mapel }}</td>
                <td>Pertemuan {{ $pertemuan++ }}</td>
                <td>{{ $item->nilai }}</td>

                @if($firstRow)
                    <td rowspan="{{ $rowspan }}" style="text-align: center; vertical-align: middle;">
                        {{ $avg }}
                    </td>
                    @php $firstRow = false; @endphp
                @endif
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>


</body>
</html>
