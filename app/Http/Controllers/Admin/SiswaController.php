<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select('siswa.*', 'users.nama', 'users.email', 'kelas.nama_kelas')
            ->get();

        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = DB::table('kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nis' => 'required|unique:siswa,nis',
            'kelas_id' => 'required',
            'alamat' => 'required'
        ]);

        DB::beginTransaction();

        try {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create siswa
            DB::table('siswa')->insert([
                'user_id' => $userId,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'alamat' => $request->alamat,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect('/admin/siswa')->with('success', 'Siswa created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create siswa: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $siswa = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->select('siswa.*', 'users.nama', 'users.email')
            ->where('siswa.id', $id)
            ->first();

        $kelas = DB::table('kelas')->get();

        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'nis' => 'required',
            'kelas_id' => 'required',
            'alamat' => 'required'
        ]);

        $siswa = DB::table('siswa')->where('id', $id)->first();

        DB::beginTransaction();

        try {
            // Update user
            DB::table('users')
                ->where('id', $siswa->user_id)
                ->update([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now()
                ]);

            // Update siswa
            DB::table('siswa')
                ->where('id', $id)
                ->update([
                    'nis' => $request->nis,
                    'kelas_id' => $request->kelas_id,
                    'alamat' => $request->alamat,
                    'updated_at' => now()
                ]);

            DB::commit();
            return redirect('/admin/siswa')->with('success', 'Siswa updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update siswa: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $siswa = DB::table('siswa')->where('id', $id)->first();
        
        DB::beginTransaction();
        
        try {
            DB::table('siswa')->where('id', $id)->delete();
            DB::table('users')->where('id', $siswa->user_id)->delete();
            
            DB::commit();
            return redirect('/admin/siswa')->with('success', 'Siswa deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete siswa: ' . $e->getMessage());
        }
    }
}