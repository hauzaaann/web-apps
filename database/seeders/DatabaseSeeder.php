<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Truncate tables in correct order
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('nilai')->truncate();
        DB::table('siswa')->truncate();
        DB::table('guru')->truncate();
        DB::table('mapel')->truncate();
        DB::table('kelas')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create 1 admin
        DB::table('users')->insert([
            'nama' => 'Admin Utama',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create 3 guru
        $guruUsers = [];
        $guruData = [
            ['nama' => 'Budi Santoso', 'nip' => '198001012010011001', 'mapel' => 'Matematika'],
            ['nama' => 'Siti Aminah', 'nip' => '198002022010012002', 'mapel' => 'Bahasa Indonesia'],
            ['nama' => 'Ahmad Hidayat', 'nip' => '198003032010013003', 'mapel' => 'IPA'],
        ];

        foreach ($guruData as $index => $g) {
            $userId = DB::table('users')->insertGetId([
                'nama' => $g['nama'],
                'email' => strtolower(str_replace(' ', '', $g['nama'])) . '@guru.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $guruUsers[] = [
                'user_id' => $userId,
                'nip' => $g['nip'],
                'mapel_id' => null, // Will update after mapel created
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert guru
        foreach ($guruUsers as $guru) {
            DB::table('guru')->insert($guru);
        }

        // Create mapel and assign to guru
        $mapelList = ['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'Bahasa Inggris'];
        $gurus = DB::table('guru')->get();
        
        foreach ($mapelList as $index => $mapel) {
            $guruId = $index < count($gurus) ? $gurus[$index]->id : null;
            
            $mapelId = DB::table('mapel')->insertGetId([
                'nama_mapel' => $mapel,
                'guru_id' => $guruId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update guru's mapel_id if this is their mapel
            if ($guruId) {
                DB::table('guru')->where('id', $guruId)->update(['mapel_id' => $mapelId]);
            }
        }

        // Create 3 kelas
        $kelasList = ['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1'];
        foreach ($kelasList as $kelas) {
            DB::table('kelas')->insert([
                'nama_kelas' => $kelas,
                'wali_kelas_id' => null, // Will update after assigning
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Assign wali kelas (first 3 gurus as wali kelas)
        $gurus = DB::table('guru')->limit(3)->get();
        $kelas = DB::table('kelas')->get();
        
        foreach ($gurus as $index => $guru) {
            if (isset($kelas[$index])) {
                DB::table('kelas')
                    ->where('id', $kelas[$index]->id)
                    ->update(['wali_kelas_id' => $guru->id]);
            }
        }

        // Create 10 siswa
        $siswaData = [
            ['nama' => 'Ahmad Siswa', 'nis' => '2024001', 'kelas' => 'X IPA 1', 'alamat' => 'Jl. Merdeka No. 1'],
            ['nama' => 'Budi Siswa', 'nis' => '2024002', 'kelas' => 'X IPA 1', 'alamat' => 'Jl. Merdeka No. 2'],
            ['nama' => 'Citra Siswa', 'nis' => '2024003', 'kelas' => 'X IPA 2', 'alamat' => 'Jl. Merdeka No. 3'],
            ['nama' => 'Dewi Siswa', 'nis' => '2024004', 'kelas' => 'X IPA 2', 'alamat' => 'Jl. Merdeka No. 4'],
            ['nama' => 'Eko Siswa', 'nis' => '2024005', 'kelas' => 'XI IPA 1', 'alamat' => 'Jl. Merdeka No. 5'],
            ['nama' => 'Fajar Siswa', 'nis' => '2024006', 'kelas' => 'XI IPA 1', 'alamat' => 'Jl. Merdeka No. 6'],
            ['nama' => 'Gita Siswa', 'nis' => '2024007', 'kelas' => 'XI IPA 2', 'alamat' => 'Jl. Merdeka No. 7'],
            ['nama' => 'Hadi Siswa', 'nis' => '2024008', 'kelas' => 'XI IPA 2', 'alamat' => 'Jl. Merdeka No. 8'],
            ['nama' => 'Indah Siswa', 'nis' => '2024009', 'kelas' => 'XII IPA 1', 'alamat' => 'Jl. Merdeka No. 9'],
            ['nama' => 'Joko Siswa', 'nis' => '2024010', 'kelas' => 'XII IPA 1', 'alamat' => 'Jl. Merdeka No. 10'],
        ];

        foreach ($siswaData as $s) {
            $kelas = DB::table('kelas')->where('nama_kelas', $s['kelas'])->first();
            
            $userId = DB::table('users')->insertGetId([
                'nama' => $s['nama'],
                'email' => strtolower(str_replace(' ', '', $s['nama'])) . '@siswa.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('siswa')->insert([
                'user_id' => $userId,
                'nis' => $s['nis'],
                'kelas_id' => $kelas->id,
                'alamat' => $s['alamat'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create sample nilai for each siswa
        $siswa = DB::table('siswa')->get();
        $mapel = DB::table('mapel')->get();

        foreach ($siswa as $s) {
            foreach ($mapel as $m) {
                // Random nilai between 60-100 for each mapel
                DB::table('nilai')->insert([
                    'siswa_id' => $s->id,
                    'mapel_id' => $m->id,
                    'nilai' => rand(60, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}