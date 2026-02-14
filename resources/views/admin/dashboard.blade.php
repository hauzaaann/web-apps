@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Admin Dashboard</h2>
    
    <h3>Statistics</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Total Siswa</th>
            <th>Total Guru</th>
            <th>Total Kelas</th>
            <th>Total Mapel</th>
        </tr>
        <tr>
            <td>{{ $totalSiswa }}</td>
            <td>{{ $totalGuru }}</td>
            <td>{{ $totalKelas }}</td>
            <td>{{ $totalMapel }}</td>
        </tr>
    </table>

    <h3>Recent Siswa</h3>
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentSiswa as $siswa)
            <tr>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->nis }}</td>
                <td>{{ $siswa->nama_kelas }}</td>
                <td>{{ $siswa->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="/admin/siswa">Manage Siswa</a></li>
            <li><a href="/admin/guru">Manage Guru</a></li>
            <li><a href="/admin/kelas">Manage Kelas</a></li>
            <li><a href="/admin/mapel">Manage Mapel</a></li>
            <li><a href="/admin/nilai">Manage Nilai</a></li>
        </ul>
    </div>
@endsection