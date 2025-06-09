<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport Siswa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            width: 60px;
            position: absolute;
            top: 20px;
            left: 30px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        .student-info {
            margin-top: 20px;
            line-height: 1.6;
        }

        .student-info span {
            display: inline-block;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .desc {
            text-align: left;
        }

        .signature {
            margin-top: 50px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            padding: 40px 0 0 0;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="logo_sekolah.png" alt="Logo Sekolah">
    <div class="title">LAPORAN HASIL BELAJAR</div>
    <div>Semester: Ganjil</div>
    <div>Tahun Ajaran: 2024/2025</div>
</div>

<style>
    .student-info span.label {
        display: inline-block;
        min-width: 80px;
    }
</style>

<div class="student-info">
    <div><span class="label">Nama</span>: {{ $siswa->nm_siswa }}</div>
    <div><span class="label">NIS</span>: {{ $siswa->nisn }}</div>
    <div><span class="label">Kelas</span>: {{ $siswa->nm_kelas ?? '-' }}</div>
    <div><span class="label">Sekolah</span>: YPAC</div>
</div>

<h3>A. Pengetahuan dan Keterampilan</h3>
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Mata Pelajaran</th>
        <th>Nilai Pengetahuan</th>
        <th>Deskripsi</th>
        <th>Nilai Keterampilan</th>
        <th>Deskripsi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($nilai_harian as $nilai)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $nilai->nm_mapel }}</td>
            <td>{{ round($nilai->avg, 2) }}</td>
            <td class="desc">........</td>
            <td>...</td>
            <td class="desc">....</td>
        </tr>
    @endforeach

    </tbody>
</table>

<h3>B. Sikap Spiritual dan Sosial</h3>
<table>
    <tr>
        <th>Aspek</th>
        <th>Deskripsi</th>
    </tr>
    <tr>
        <td>...</td>
        <td class="desc">...</td>
    </tr>
    <tr>
        <td>...</td>
        <td class="desc">...</td>
    </tr>
</table>

<h3>C. Ketidakhadiran</h3>
<table>
    <tr>
        <td>Sakit</td>
        <td>: ... hari</td>
        <td>Izin</td>
        <td>: ... hari</td>
        <td>Tanpa Keterangan</td>
        <td>: ... hari</td>
    </tr>
</table>

<table class="signature">
    <tr>
        <td>Orang Tua/Wali</td>
        <td>Wali Kelas</td>
        <td>Kepala Sekolah</td>
    </tr>
    <tr>
        <td>(____________________)</td>
        <td>(____________________)</td>
        <td>(____________________)</td>
    </tr>
</table>

</body>
</html>
