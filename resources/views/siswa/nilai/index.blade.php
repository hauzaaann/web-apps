@extends('layouts.main')

@section('title', 'Nilai Saya')

@section('content')
    <h2>Nilai Saya</h2>
    
    <h3>Statistik</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Total Mapel</th>
            <th>Rata-rata</th>
            <th>Tertinggi</th>
            <th>Terendah</th>
        </tr>
        <tr>
            <td>{{ $totalNilai }}</td>
            <td>{{ $averageNilai }}</td>
            <td>{{ $highestNilai }}</td>
            <td>{{ $lowestNilai }}</td>
        </tr>
    </table>
    
    <br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $n)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $n->nama_mapel }}</td>
                <td>{{ $n->guru_nama }}</td>
                <td>{{ $n->nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection