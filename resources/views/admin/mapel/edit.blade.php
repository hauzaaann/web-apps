@extends('layouts.main')

@section('title', 'Edit Mapel')

@section('content')
    <h2>Edit Mata Pelajaran</h2>
    
    <form method="POST" action="/admin/mapel/{{ $mapel->id }}">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Nama Mapel:</td>
                <td><input type="text" name="nama_mapel" value="{{ $mapel->nama_mapel }}" required></td>
            </tr>
            <tr>
                <td>Guru Pengajar:</td>
                <td>
                    <select name="guru_id">
                        <option value="">Pilih Guru (Opsional)</option>
                        @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ $mapel->guru_id == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Update</button>
                    <a href="/admin/mapel"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection