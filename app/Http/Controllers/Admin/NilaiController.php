<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('nilai')
            ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('mapel', 'nilai.mapel_id', '=', 'mapel.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select('nilai.*', 'users.nama as siswa_nama', 'mapel.nama_mapel', 'kelas.nama_kelas');

        // Filter by kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('siswa.kelas_id', $request->kelas_id);
        }

        // Filter by mapel
        if ($request->has('mapel_id') && $request->mapel_id) {
            $query->where('nilai.mapel_id', $request->mapel_id);
        }

        $nilai = $query->get();
        $kelas = DB::table('kelas')->get();
        $mapel = DB::table('mapel')->get();

        return view('admin.nilai.index', compact('nilai', 'kelas', 'mapel'));
    }

    public function create()
    {
        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select('siswa.id', 'users.nama', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('users.nama')
            ->get();

        $mapel = DB::table('mapel')->get();

        return view('admin.nilai.create', compact('siswa', 'mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mapel,id',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        // Check if nilai already exists
        $exists = DB::table('nilai')
            ->where('siswa_id', $request->siswa_id)
            ->where('mapel_id', $request->mapel_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nilai for this student and subject already exists');
        }

        DB::table('nilai')->insert([
            'siswa_id' => $request->siswa_id,
            'mapel_id' => $request->mapel_id,
            'nilai' => $request->nilai,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/admin/nilai')->with('success', 'Nilai created successfully');
    }

    public function edit($id)
    {
        $nilai = DB::table('nilai')->where('id', $id)->first();
        
        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->select('siswa.id', 'users.nama')
            ->get();

        $mapel = DB::table('mapel')->get();

        return view('admin.nilai.edit', compact('nilai', 'siswa', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mapel,id',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        // Check if another nilai exists (excluding current)
        $exists = DB::table('nilai')
            ->where('siswa_id', $request->siswa_id)
            ->where('mapel_id', $request->mapel_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nilai for this student and subject already exists');
        }

        DB::table('nilai')
            ->where('id', $id)
            ->update([
                'siswa_id' => $request->siswa_id,
                'mapel_id' => $request->mapel_id,
                'nilai' => $request->nilai,
                'updated_at' => now()
            ]);

        return redirect('/admin/nilai')->with('success', 'Nilai updated successfully');
    }

    public function destroy($id)
    {
        DB::table('nilai')->where('id', $id)->delete();
        return redirect('/admin/nilai')->with('success', 'Nilai deleted successfully');
    }

    public function export(Request $request)
    {
        $query = DB::table('nilai')
            ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('mapel', 'nilai.mapel_id', '=', 'mapel.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select(
                'users.nama as nama_siswa',
                'siswa.nis',
                'kelas.nama_kelas',
                'mapel.nama_mapel',
                'nilai.nilai'
            )
            ->orderBy('kelas.nama_kelas')
            ->orderBy('users.nama')
            ->orderBy('mapel.nama_mapel');

        // Apply filters if any
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('siswa.kelas_id', $request->kelas_id);
        }

        if ($request->has('mapel_id') && $request->mapel_id) {
            $query->where('nilai.mapel_id', $request->mapel_id);
        }

        $nilai = $query->get();

        // Generate CSV
        $filename = 'nilai_export_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add headers
        fputcsv($handle, ['Nama Siswa', 'NIS', 'Kelas', 'Mata Pelajaran', 'Nilai']);

        // Add data
        foreach ($nilai as $n) {
            fputcsv($handle, [
                $n->nama_siswa,
                $n->nis,
                $n->nama_kelas,
                $n->nama_mapel,
                $n->nilai
            ]);
        }

        fclose($handle);
        exit;
    }
}