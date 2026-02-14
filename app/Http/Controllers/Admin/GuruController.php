<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->leftJoin('mapel', 'guru.mapel_id', '=', 'mapel.id')
            ->select('guru.*', 'users.nama', 'users.email', 'mapel.nama_mapel')
            ->get();

        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        $mapel = DB::table('mapel')->whereNull('guru_id')->get();
        return view('admin.guru.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nip' => 'required|unique:guru,nip',
            'mapel_id' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create guru
            DB::table('guru')->insert([
                'user_id' => $userId,
                'nip' => $request->nip,
                'mapel_id' => $request->mapel_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update mapel if assigned
            if ($request->mapel_id) {
                DB::table('mapel')
                    ->where('id', $request->mapel_id)
                    ->update(['guru_id' => DB::getPdo()->lastInsertId()]);
            }

            DB::commit();
            return redirect('/admin/guru')->with('success', 'Guru created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create guru: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->select('guru.*', 'users.nama', 'users.email')
            ->where('guru.id', $id)
            ->first();

        $mapel = DB::table('mapel')
            ->whereNull('guru_id')
            ->orWhere('guru_id', $id)
            ->get();

        return view('admin.guru.edit', compact('guru', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'nip' => 'required',
            'mapel_id' => 'nullable'
        ]);

        $guru = DB::table('guru')->where('id', $id)->first();

        DB::beginTransaction();

        try {
            // Update user
            DB::table('users')
                ->where('id', $guru->user_id)
                ->update([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now()
                ]);

            // Remove old mapel assignment
            if ($guru->mapel_id) {
                DB::table('mapel')
                    ->where('id', $guru->mapel_id)
                    ->update(['guru_id' => null]);
            }

            // Update guru
            DB::table('guru')
                ->where('id', $id)
                ->update([
                    'nip' => $request->nip,
                    'mapel_id' => $request->mapel_id,
                    'updated_at' => now()
                ]);

            // Assign new mapel
            if ($request->mapel_id) {
                DB::table('mapel')
                    ->where('id', $request->mapel_id)
                    ->update(['guru_id' => $id]);
            }

            DB::commit();
            return redirect('/admin/guru')->with('success', 'Guru updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update guru: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $guru = DB::table('guru')->where('id', $id)->first();
        
        DB::beginTransaction();
        
        try {
            // Remove mapel assignment
            if ($guru->mapel_id) {
                DB::table('mapel')
                    ->where('id', $guru->mapel_id)
                    ->update(['guru_id' => null]);
            }

            DB::table('guru')->where('id', $id)->delete();
            DB::table('users')->where('id', $guru->user_id)->delete();
            
            DB::commit();
            return redirect('/admin/guru')->with('success', 'Guru deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete guru: ' . $e->getMessage());
        }
    }
}