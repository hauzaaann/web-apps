@extends('layouts.main')

@section('title', 'Tambah Siswa')

@section('content')
    <h2>Tambah Siswa</h2>
    
    <form method="POST" action="/admin/siswa">
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
                <td>NIS:</td>
                <td><input type="text" name="nis" required></td>
            </tr>
            <tr>
                <td>Kelas:</td>
                <td>
                    <select name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td><textarea name="alamat" required></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan</button>
                    <a href="/admin/siswa"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection