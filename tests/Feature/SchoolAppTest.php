<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SchoolAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeding()
    {
        $this->seed();

        $this->assertEquals(1, DB::table('users')->where('role', 'admin')->count());
        $this->assertEquals(3, DB::table('users')->where('role', 'guru')->count());
        $this->assertEquals(10, DB::table('users')->where('role', 'siswa')->count());
        $this->assertEquals(5, DB::table('kelas')->count());
        $this->assertEquals(5, DB::table('mapel')->count());
    }

    public function test_admin_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@sekolah.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertTrue(session()->has('user'));
        $this->assertEquals('admin', session('user')->role);
    }

    public function test_guru_login()
    {
        $response = $this->post('/login', [
            'email' => 'budisantoso@guru.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/guru/dashboard');
        $this->assertEquals('guru', session('user')->role);
    }

    public function test_siswa_login()
    {
        $response = $this->post('/login', [
            'email' => 'ahmadsiswa@siswa.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/siswa/dashboard');
        $this->assertEquals('siswa', session('user')->role);
    }
}
