@extends('layouts.main')

@section('title', 'Manage Mapel')

@section('content')
    <h2>Data Mata Pelajaran</h2>
    
    <a href="/admin/mapel/create"><button>Tambah Mapel</button></a>
    
    <br><br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mapel</th>
                <th>Guru Pengajar</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mapel as $index => $m)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $m->nama_mapel }}</td>
                <td>{{ $m->guru_nama ?? '-' }}</td>
                <td>
                    <a href="/admin/mapel/{{ $m->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/mapel/{{ $m->id }}" style="display:inline">
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