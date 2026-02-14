@extends('layouts.main')

@section('title', 'Edit Siswa')

@section('content')
    <h2>Edit Siswa</h2>
    
    <form method="POST" action="/admin/siswa/{{ $siswa->id }}">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Nama Lengkap:</td>
                <td><input type="text" name="nama" value="{{ $siswa->nama }}" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="{{ $siswa->email }}" required></td>
            </tr>
            <tr>
                <td>NIS:</td>
                <td><input type="text" name="nis" value="{{ $siswa->nis }}" required></td>
            </tr>
            <tr>
                <td>Kelas:</td>
                <td>
                    <select name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td><textarea name="alamat" required>{{ $siswa->alamat }}</textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Update</button>
                    <a href="/admin/siswa"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection