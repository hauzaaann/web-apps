@extends('layouts.main')

@section('title', 'Tambah Guru')

@section('content')
    <h2>Tambah Guru</h2>
    
    <form method="POST" action="/admin/guru">
        @csrf
        <table>
            <tr>
                <td>Nama Lengkap:</td>
                <td><input type="text" name="nama" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required min="6"></td>
            </tr>
            <tr>
                <td>NIP:</td>
                <td><input type="text" name="nip" required></td>
            </tr>
            <tr>
                <td>Mata Pelajaran:</td>
                <td>
                    <select name="mapel_id">
                        <option value="">Pilih Mapel (Opsional)</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan</button>
                    <a href="/admin/guru"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection