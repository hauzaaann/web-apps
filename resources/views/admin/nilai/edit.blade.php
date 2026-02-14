@extends('layouts.main')

@section('title', 'Edit Nilai')

@section('content')
    <h2>Edit Nilai</h2>
    
    <form method="POST" action="/admin/nilai/{{ $nilai->id }}">
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
                <td>Mata Pelajaran:</td>
                <td>
                    <select name="mapel_id" required>
                        <option value="">Pilih Mapel</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ $nilai->mapel_id == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
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
                    <a href="/admin/nilai"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection