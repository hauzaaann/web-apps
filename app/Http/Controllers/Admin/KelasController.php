<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.wali_kelas_id', '=', 'guru.id')
            ->leftJoin('users', 'guru.user_id', '=', 'users.id')
            ->select('kelas.*', 'users.nama as wali_kelas_nama')
            ->get();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->select('guru.id', 'users.nama')
            ->whereNotIn('guru.id', function($query) {
                $query->select('wali_kelas_id')->from('kelas')->whereNotNull('wali_kelas_id');
            })
            ->get();

        return view('admin.kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
            'wali_kelas_id' => 'nullable|exists:guru,id'
        ]);

        DB::table('kelas')->insert([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/admin/kelas')->with('success', 'Kelas created successfully');
    }

    public function edit($id)
    {
        $kelas = DB::table('kelas')->where('id', $id)->first();
        
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->select('guru.id', 'users.nama')
            ->whereNotIn('guru.id', function($query) use ($id) {
                $query->select('wali_kelas_id')->from('kelas')
                    ->whereNotNull('wali_kelas_id')
                    ->where('id', '!=', $id);
            })
            ->get();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id,
            'wali_kelas_id' => 'nullable|exists:guru,id'
        ]);

        DB::table('kelas')
            ->where('id', $id)
            ->update([
                'nama_kelas' => $request->nama_kelas,
                'wali_kelas_id' => $request->wali_kelas_id,
                'updated_at' => now()
            ]);

        return redirect('/admin/kelas')->with('success', 'Kelas updated successfully');
    }

    public function destroy($id)
    {
        // Check if kelas has students
        $siswaCount = DB::table('siswa')->where('kelas_id', $id)->count();
        
        if ($siswaCount > 0) {
            return back()->with('error', 'Cannot delete kelas that has students');
        }

        DB::table('kelas')->where('id', $id)->delete();
        return redirect('/admin/kelas')->with('success', 'Kelas deleted successfully');
    }
}