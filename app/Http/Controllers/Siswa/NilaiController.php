<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        // Get siswa data
        $siswa = DB::table('siswa')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return redirect('/siswa/dashboard')->with('error', 'Siswa data not found');
        }

        // Get all nilai for this siswa
        $nilai = DB::table('nilai')
            ->join('mapel', 'nilai.mapel_id', '=', 'mapel.id')
            ->join('guru', 'mapel.guru_id', '=', 'guru.id')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->where('nilai.siswa_id', $siswa->id)
            ->select(
                'nilai.*',
                'mapel.nama_mapel',
                'users.nama as guru_nama'
            )
            ->orderBy('mapel.nama_mapel')
            ->get();

        // Calculate statistics
        $totalNilai = $nilai->count();
        $averageNilai = $totalNilai > 0 ? round($nilai->avg('nilai'), 2) : 0;
        $highestNilai = $totalNilai > 0 ? $nilai->max('nilai') : 0;
        $lowestNilai = $totalNilai > 0 ? $nilai->min('nilai') : 0;

        return view('siswa.nilai.index', compact(
            'nilai',
            'totalNilai',
            'averageNilai',
            'highestNilai',
            'lowestNilai'
        ));
    }
}