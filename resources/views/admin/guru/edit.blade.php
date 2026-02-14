@extends('layouts.main')

@section('title', 'Edit Guru')

@section('content')
    <h2>Edit Guru</h2>
    
    <form method="POST" action="/admin/guru/{{ $guru->id }}">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Nama Lengkap:</td>
                <td><input type="text" name="nama" value="{{ $guru->nama }}" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="{{ $guru->email }}" required></td>
            </tr>
            <tr>
                <td>NIP:</td>
                <td><input type="text" name="nip" value="{{ $guru->nip }}" required></td>
            </tr>
            <tr>
                <td>Mata Pelajaran:</td>
                <td>
                    <select name="mapel_id">
                        <option value="">Pilih Mapel (Opsional)</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ $guru->mapel_id == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Update</button>
                    <a href="/admin/guru"><button type="button">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
@endsection