@extends('layouts.main')

@section('title', 'Manage Nilai - Guru')

@section('content')
    <h2>Data Nilai - {{ $mapel->nama_mapel }}</h2>
    
    <a href="/guru/nilai/create"><button>Input Nilai Baru</button></a>
    
    <br><br>
    
    <form method="GET" action="/guru/nilai">
        <table>
            <tr>
                <td>Filter Kelas:</td>
                <td>
                    <select name="kelas_id">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td><button type="submit">Filter</button></td>
            </tr>
        </table>
    </form>
    
    <br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nilai</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $n)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $n->siswa_nama }}</td>
                <td>{{ $n->nama_kelas }}</td>
                <td>{{ $n->nilai }}</td>
                <td>
                    <a href="/guru/nilai/{{ $n->id }}/edit">Edit</a>
                    <form method="POST" action="/guru/nilai/{{ $n->id }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus data?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection