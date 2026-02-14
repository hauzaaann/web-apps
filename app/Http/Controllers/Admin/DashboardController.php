<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = DB::table('siswa')->count();
        $totalGuru = DB::table('guru')->count();
        $totalKelas = DB::table('kelas')->count();
        $totalMapel = DB::table('mapel')->count();
        
        $recentSiswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select('siswa.*', 'users.nama', 'kelas.nama_kelas')
            ->orderBy('siswa.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 
            'totalGuru', 
            'totalKelas', 
            'totalMapel',
            'recentSiswa'
        ));
    }
}