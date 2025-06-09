<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #fff;
            color: #000;
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
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        @media print {
            body {
                margin: 0;
            }

            h2 {
                margin-top: 0;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>

<h2>Laporan Nilai Siswa</h2>

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Mata Pelajaran</th>
        <th>Materi</th>
        <th>Nilai</th>
    </tr>
    </thead>
    <tbody>
    @foreach($nilai as $nilai)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $nilai->nm_siswa }}</td>
            <td>{{ $nilai->nm_mapel  }}</td>
            <td>{{ $nilai->materi  }}</td>
            <td>{{ $nilai->nilai  }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

</body>
</html>
