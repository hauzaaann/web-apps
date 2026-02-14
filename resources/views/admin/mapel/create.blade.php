@extends('layouts.main')

@section('title', 'Tambah Mapel')

@section('content')
    <h2>Tambah Mata Pelajaran</h2>
    
    <form method="POST" action="/admin/mapel">
        @csrf
        <table>
            <tr>
                <td>Nama Mapel:</td>
                <td><input type="text" name="nama_mapel" required></td>
            </tr>
            <tr>
                <td>Guru Pengajar:</td>
                <td>
                    <select name="guru_id">
                        <option value="">Pilih Guru (Opsional)</option>
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
                    <a href="/admin/mapel"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection