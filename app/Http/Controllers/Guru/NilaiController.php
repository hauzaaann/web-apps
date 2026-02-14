<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NilaiController extends Controller
{
    private function getGuruMapel()
    {
        $user = Session::get('user');
        
        $guru = DB::table('guru')
            ->where('user_id', $user->id)
            ->first();

        if (!$guru || !$guru->mapel_id) {
            return null;
        }

        return DB::table('mapel')->where('id', $guru->mapel_id)->first();
    }

    public function index(Request $request)
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $query = DB::table('nilai')
            ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('nilai.mapel_id', $mapel->id)
            ->select('nilai.*', 'users.nama as siswa_nama', 'kelas.nama_kelas');

        // Filter by kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('siswa.kelas_id', $request->kelas_id);
        }

        $nilai = $query->get();
        $kelas = DB::table('kelas')->get();

        return view('guru.nilai.index', compact('nilai', 'kelas', 'mapel'));
    }

    public function create()
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select('siswa.id', 'users.nama', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('users.nama')
            ->get();

        // Filter out students who already have nilai for this mapel
        $existingSiswaIds = DB::table('nilai')
            ->where('mapel_id', $mapel->id)
            ->pluck('siswa_id')
            ->toArray();

        return view('guru.nilai.create', compact('siswa', 'mapel', 'existingSiswaIds'));
    }

    public function store(Request $request)
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        // Check if nilai already exists
        $exists = DB::table('nilai')
            ->where('siswa_id', $request->siswa_id)
            ->where('mapel_id', $mapel->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nilai for this student already exists');
        }

        DB::table('nilai')->insert([
            'siswa_id' => $request->siswa_id,
            'mapel_id' => $mapel->id,
            'nilai' => $request->nilai,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/guru/nilai')->with('success', 'Nilai created successfully');
    }

    public function edit($id)
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $nilai = DB::table('nilai')
            ->where('id', $id)
            ->where('mapel_id', $mapel->id)
            ->first();

        if (!$nilai) {
            return redirect('/guru/nilai')->with('error', 'Nilai not found');
        }

        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->select('siswa.id', 'users.nama')
            ->get();

        return view('guru.nilai.edit', compact('nilai', 'siswa', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        $nilai = DB::table('nilai')
            ->where('id', $id)
            ->where('mapel_id', $mapel->id)
            ->first();

        if (!$nilai) {
            return redirect('/guru/nilai')->with('error', 'Nilai not found');
        }

        DB::table('nilai')
            ->where('id', $id)
            ->update([
                'siswa_id' => $request->siswa_id,
                'nilai' => $request->nilai,
                'updated_at' => now()
            ]);

        return redirect('/guru/nilai')->with('success', 'Nilai updated successfully');
    }

    public function destroy($id)
    {
        $mapel = $this->getGuruMapel();
        
        if (!$mapel) {
            return redirect('/guru/dashboard')->with('error', 'You are not assigned to any subject');
        }

        $nilai = DB::table('nilai')
            ->where('id', $id)
            ->where('mapel_id', $mapel->id)
            ->first();

        if (!$nilai) {
            return redirect('/guru/nilai')->with('error', 'Nilai not found');
        }

        DB::table('nilai')->where('id', $id)->delete();
        return redirect('/guru/nilai')->with('success', 'Nilai deleted successfully');
    }
}