@extends('layouts.main')

@section('title', 'Guru Dashboard')

@section('content')
    <h2>Guru Dashboard</h2>
    
    <h3>Informasi Guru</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>NIP</th>
            <td>{{ $guru->nip }}</td>
        </tr>
        <tr>
            <th>Mata Pelajaran</th>
            <td>{{ $mapel->nama_mapel ?? 'Belum ada mapel' }}</td>
        </tr>
    </table>

    <h3>Statistik</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Total Siswa</th>
            <th>Total Kelas</th>
        </tr>
        <tr>
            <td>{{ $totalSiswa }}</td>
            <td>{{ $totalKelas }}</td>
        </tr>
    </table>

    @if($mapel)
    <h3>Recent Nilai Input</h3>
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Nilai</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentNilai as $nilai)
            <tr>
                <td>{{ $nilai->siswa_nama }}</td>
                <td>{{ $nilai->nama_kelas }}</td>
                <td>{{ $nilai->nilai }}</td>
                <td>{{ $nilai->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="margin-top: 20px;">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="/guru/nilai">Manage Nilai</a></li>
        </ul>
    </div>
@endsection