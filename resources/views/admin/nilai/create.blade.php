@extends('layouts.main')

@section('title', 'Input Nilai')

@section('content')
    <h2>Input Nilai</h2>
    
    <form method="POST" action="/admin/nilai">
        @csrf
        <table>
            <tr>
                <td>Siswa:</td>
                <td>
                    <select name="siswa_id" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }} - {{ $s->nama_kelas }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Mata Pelajaran:</td>
                <td>
                    <select name="mapel_id" required>
                        <option value="">Pilih Mapel</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nilai:</td>
                <td><input type="number" name="nilai" min="0" max="100" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan</button>
                    <a href="/admin/nilai"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection