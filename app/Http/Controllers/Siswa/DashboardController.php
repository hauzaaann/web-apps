<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        // Get siswa data
        $siswa = DB::table('siswa')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return redirect('/login')->with('error', 'Siswa data not found');
        }

        // Get kelas info
        $kelas = DB::table('kelas')
            ->where('id', $siswa->kelas_id)
            ->first();

        // Get wali kelas info
        $waliKelas = null;
        if ($kelas && $kelas->wali_kelas_id) {
            $waliKelas = DB::table('guru')
                ->join('users', 'guru.user_id', '=', 'users.id')
                ->where('guru.id', $kelas->wali_kelas_id)
                ->select('users.nama')
                ->first();
        }

        // Get statistics
        $totalMapel = DB::table('mapel')->count();
        $totalNilai = DB::table('nilai')
            ->where('siswa_id', $siswa->id)
            ->count();

        // Get average nilai
        $avgNilai = DB::table('nilai')
            ->where('siswa_id', $siswa->id)
            ->avg('nilai');

        return view('siswa.dashboard', compact(
            'siswa', 
            'kelas', 
            'waliKelas', 
            'totalMapel', 
            'totalNilai', 
            'avgNilai'
        ));
    }
}