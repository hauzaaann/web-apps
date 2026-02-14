<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        // Get guru data
        $guru = DB::table('guru')
            ->where('user_id', $user->id)
            ->first();

        // Get mapel that this guru teaches
        $mapel = DB::table('mapel')
            ->where('guru_id', $guru->id)
            ->first();

        // Get statistics
        $totalSiswa = DB::table('siswa')->count();
        $totalKelas = DB::table('kelas')->count();
        
        // Get recent nilai entries by this guru's mapel
        $recentNilai = [];
        if ($mapel) {
            $recentNilai = DB::table('nilai')
                ->join('siswa', 'nilai.siswa_id', '=', 'siswa.id')
                ->join('users', 'siswa.user_id', '=', 'users.id')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->where('nilai.mapel_id', $mapel->id)
                ->select('nilai.*', 'users.nama as siswa_nama', 'kelas.nama_kelas')
                ->orderBy('nilai.created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('guru.dashboard', compact('guru', 'mapel', 'totalSiswa', 'totalKelas', 'recentNilai'));
    }
}