@extends('layouts.main')

@section('title', 'Siswa Dashboard')

@section('content')
    <h2>Siswa Dashboard</h2>
    
    <h3>Informasi Siswa</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>NIS</th>
            <td>{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <th>Wali Kelas</th>
            <td>{{ $waliKelas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $siswa->alamat }}</td>
        </tr>
    </table>

    <h3>Statistik Nilai</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Total Mapel</th>
            <th>Total Nilai</th>
            <th>Rata-rata</th>
        </tr>
        <tr>
            <td>{{ $totalMapel }}</td>
            <td>{{ $totalNilai }}</td>
            <td>{{ number_format($avgNilai, 2) }}</td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="/siswa/nilai">Lihat Nilai Saya</a></li>
        </ul>
    </div>
@endsection