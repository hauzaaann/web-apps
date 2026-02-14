@extends('layouts.main')

@section('title', 'Manage Nilai')

@section('content')
    <h2>Data Nilai</h2>
    
    <div style="margin-bottom: 20px;">
        <a href="/admin/nilai/create"><button>Input Nilai</button></a>
        <a href="/admin/nilai/export/csv?kelas_id={{ request('kelas_id') }}&mapel_id={{ request('mapel_id') }}"><button>Export CSV</button></a>
    </div>
    
    <form method="GET" action="/admin/nilai">
        <table>
            <tr>
                <td>Filter Kelas:</td>
                <td>
                    <select name="kelas_id">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>Filter Mapel:</td>
                <td>
                    <select name="mapel_id">
                        <option value="">Semua Mapel</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td><button type="submit">Filter</button></td>
            </tr>
        </table>
    </form>
    
    <br>
    
    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $n)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $n->siswa_nama }}</td>
                <td>{{ $n->nama_kelas }}</td>
                <td>{{ $n->nama_mapel }}</td>
                <td>{{ $n->nilai }}</td>
                <td>
                    <a href="/admin/nilai/{{ $n->id }}/edit">Edit</a>
                    <form method="POST" action="/admin/nilai/{{ $n->id }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus data?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection