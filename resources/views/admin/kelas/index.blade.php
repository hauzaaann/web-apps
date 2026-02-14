@extends('layouts.main')

@section('title', 'Manage Kelas')

@section('content')
    <h2>Data Kelas</h2>
    
    <a href="/admin/kelas/create"><button>Tambah Kelas</button></a>
    
    <br><br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $index => $k)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $k->nama_kelas }}</td>
                <td>{{ $k->wali_kelas_nama ?? '-' }}</td>
                <td>
                    <a href="/admin/kelas/{{ $k->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/kelas/{{ $k->id }}" style="display:inline">
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