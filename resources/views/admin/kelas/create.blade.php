@extends('layouts.main')

@section('title', 'Tambah Kelas')

@section('content')
    <h2>Tambah Kelas</h2>
    
    <form method="POST" action="/admin/kelas">
        @csrf
        <table>
            <tr>
                <td>Nama Kelas:</td>
                <td><input type="text" name="nama_kelas" required></td>
            </tr>
            <tr>
                <td>Wali Kelas:</td>
                <td>
                    <select name="wali_kelas_id">
                        <option value="">Pilih Wali Kelas (Opsional)</option>
                        @foreach($guru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan</button>
                    <a href="/admin/kelas"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection