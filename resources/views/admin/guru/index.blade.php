@extends('layouts.main')

@section('title', 'Manage Guru')

@section('content')
    <h2>Data Guru</h2>
    
    <a href="/admin/guru/create"><button>Tambah Guru</button></a>
    
    <br><br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIP</th>
                <th>Mata Pelajaran</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guru as $index => $g)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $g->nama }}</td>
                <td>{{ $g->email }}</td>
                <td>{{ $g->nip }}</td>
                <td>{{ $g->nama_mapel ?? '-' }}</td>
                <td>
                    <a href="/admin/guru/{{ $g->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/guru/{{ $g->id }}" style="display:inline">
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