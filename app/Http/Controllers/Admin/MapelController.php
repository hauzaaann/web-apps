<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = DB::table('mapel')
            ->leftJoin('guru', 'mapel.guru_id', '=', 'guru.id')
            ->leftJoin('users', 'guru.user_id', '=', 'users.id')
            ->select('mapel.*', 'users.nama as guru_nama')
            ->get();

        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->select('guru.id', 'users.nama')
            ->whereNull('guru.mapel_id')
            ->get();

        return view('admin.mapel.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mapel,nama_mapel',
            'guru_id' => 'nullable|exists:guru,id'
        ]);

        DB::beginTransaction();

        try {
            $mapelId = DB::table('mapel')->insertGetId([
                'nama_mapel' => $request->nama_mapel,
                'guru_id' => $request->guru_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($request->guru_id) {
                DB::table('guru')
                    ->where('id', $request->guru_id)
                    ->update(['mapel_id' => $mapelId]);
            }

            DB::commit();
            return redirect('/admin/mapel')->with('success', 'Mapel created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create mapel: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $mapel = DB::table('mapel')->where('id', $id)->first();
        
        $guru = DB::table('guru')
            ->join('users', 'guru.user_id', '=', 'users.id')
            ->select('guru.id', 'users.nama')
            ->whereNull('guru.mapel_id')
            ->orWhere('guru.id', $mapel->guru_id)
            ->get();

        return view('admin.mapel.edit', compact('mapel', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mapel,nama_mapel,' . $id,
            'guru_id' => 'nullable|exists:guru,id'
        ]);

        $oldMapel = DB::table('mapel')->where('id', $id)->first();

        DB::beginTransaction();

        try {
            // Remove old guru assignment
            if ($oldMapel->guru_id) {
                DB::table('guru')
                    ->where('id', $oldMapel->guru_id)
                    ->update(['mapel_id' => null]);
            }

            // Update mapel
            DB::table('mapel')
                ->where('id', $id)
                ->update([
                    'nama_mapel' => $request->nama_mapel,
                    'guru_id' => $request->guru_id,
                    'updated_at' => now()
                ]);

            // Assign new guru
            if ($request->guru_id) {
                DB::table('guru')
                    ->where('id', $request->guru_id)
                    ->update(['mapel_id' => $id]);
            }

            DB::commit();
            return redirect('/admin/mapel')->with('success', 'Mapel updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update mapel: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Check if mapel has nilai
        $nilaiCount = DB::table('nilai')->where('mapel_id', $id)->count();
        
        if ($nilaiCount > 0) {
            return back()->with('error', 'Cannot delete mapel that has nilai records');
        }

        $mapel = DB::table('mapel')->where('id', $id)->first();

        DB::beginTransaction();

        try {
            if ($mapel->guru_id) {
                DB::table('guru')
                    ->where('id', $mapel->guru_id)
                    ->update(['mapel_id' => null]);
            }

            DB::table('mapel')->where('id', $id)->delete();

            DB::commit();
            return redirect('/admin/mapel')->with('success', 'Mapel deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete mapel: ' . $e->getMessage());
        }
    }
}