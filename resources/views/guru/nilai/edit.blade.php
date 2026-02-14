@extends('layouts.main')

@section('title', 'Edit Nilai - Guru')

@section('content')
    <h2>Edit Nilai - {{ $mapel->nama_mapel }}</h2>
    
    <form method="POST" action="/guru/nilai/{{ $nilai->id }}">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Siswa:</td>
                <td>
                    <select name="siswa_id" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ $nilai->siswa_id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nilai:</td>
                <td><input type="number" name="nilai" value="{{ $nilai->nilai }}" min="0" max="100" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Update</button>
                    <a href="/guru/nilai"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection