@extends('layouts.main')

@section('title', 'Manage Siswa')

@section('content')
    <h2>Data Siswa</h2>
    
    <a href="/admin/siswa/create"><button>Tambah Siswa</button></a>
    
    <br><br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $index => $s)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nama_kelas }}</td>
                <td>{{ $s->alamat }}</td>
                <td>
                    <a href="/admin/siswa/{{ $s->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/siswa/{{ $s->id }}" style="display:inline">
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