@extends('layouts.main')

@section('title', 'Input Nilai - Guru')

@section('content')
    <h2>Input Nilai - {{ $mapel->nama_mapel }}</h2>
    
    <form method="POST" action="/guru/nilai">
        @csrf
        <table>
            <tr>
                <td>Siswa:</td>
                <td>
                    <select name="siswa_id" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ in_array($s->id, $existingSiswaIds) ? 'disabled' : '' }}>
                            {{ $s->nama }} - {{ $s->nama_kelas }}
                            {{ in_array($s->id, $existingSiswaIds) ? '(Sudah ada nilai)' : '' }}
                        </option>
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
                    <a href="/guru/nilai"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection