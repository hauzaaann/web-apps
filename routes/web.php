<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\MapelController as AdminMapelController;
use App\Http\Controllers\Admin\NilaiController as AdminNilaiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\NilaiController as GuruNilaiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\NilaiController as SiswaNilaiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Test login status
Route::get('/test-auth', function () {
    if (Session::has('user')) {
        $user = Session::get('user');
        return "Logged in as: {$user->nama} ({$user->role})";
    } else {
        return "Not logged in";
    }
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['admin', 'session.timeout'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Siswa CRUD
    Route::get('/siswa', [AdminSiswaController::class, 'index'])->name('admin.siswa.index');
    Route::get('/siswa/create', [AdminSiswaController::class, 'create'])->name('admin.siswa.create');
    Route::post('/siswa', [AdminSiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/siswa/{id}/edit', [AdminSiswaController::class, 'edit'])->name('admin.siswa.edit');
    Route::post('/siswa/{id}', [AdminSiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/siswa/{id}', [AdminSiswaController::class, 'destroy'])->name('admin.siswa.destroy');

    // Guru CRUD
    Route::get('/guru', [AdminGuruController::class, 'index'])->name('admin.guru.index');
    Route::get('/guru/create', [AdminGuruController::class, 'create'])->name('admin.guru.create');
    Route::post('/guru', [AdminGuruController::class, 'store'])->name('admin.guru.store');
    Route::get('/guru/{id}/edit', [AdminGuruController::class, 'edit'])->name('admin.guru.edit');
    Route::post('/guru/{id}', [AdminGuruController::class, 'update'])->name('admin.guru.update');
    Route::delete('/guru/{id}', [AdminGuruController::class, 'destroy'])->name('admin.guru.destroy');

    // Kelas CRUD
    Route::get('/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas.index');
    Route::get('/kelas/create', [AdminKelasController::class, 'create'])->name('admin.kelas.create');
    Route::post('/kelas', [AdminKelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/kelas/{id}/edit', [AdminKelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::post('/kelas/{id}', [AdminKelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{id}', [AdminKelasController::class, 'destroy'])->name('admin.kelas.destroy');

    // Mapel CRUD
    Route::get('/mapel', [AdminMapelController::class, 'index'])->name('admin.mapel.index');
    Route::get('/mapel/create', [AdminMapelController::class, 'create'])->name('admin.mapel.create');
    Route::post('/mapel', [AdminMapelController::class, 'store'])->name('admin.mapel.store');
    Route::get('/mapel/{id}/edit', [AdminMapelController::class, 'edit'])->name('admin.mapel.edit');
    Route::post('/mapel/{id}', [AdminMapelController::class, 'update'])->name('admin.mapel.update');
    Route::delete('/mapel/{id}', [AdminMapelController::class, 'destroy'])->name('admin.mapel.destroy');

    // Nilai CRUD + Export
    Route::get('/nilai', [AdminNilaiController::class, 'index'])->name('admin.nilai.index');
    Route::get('/nilai/create', [AdminNilaiController::class, 'create'])->name('admin.nilai.create');
    Route::post('/nilai', [AdminNilaiController::class, 'store'])->name('admin.nilai.store');
    Route::get('/nilai/{id}/edit', [AdminNilaiController::class, 'edit'])->name('admin.nilai.edit');
    Route::post('/nilai/{id}', [AdminNilaiController::class, 'update'])->name('admin.nilai.update');
    Route::delete('/nilai/{id}', [AdminNilaiController::class, 'destroy'])->name('admin.nilai.destroy');
    Route::get('/nilai/export/csv', [AdminNilaiController::class, 'export'])->name('admin.nilai.export');
});

/*
|--------------------------------------------------------------------------
| Guru Routes
|--------------------------------------------------------------------------
*/
Route::prefix('guru')->middleware('guru')->group(function () {
    // Dashboard
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');

    // Nilai CRUD (only for their assigned mapel)
    Route::get('/nilai', [GuruNilaiController::class, 'index'])->name('guru.nilai.index');
    Route::get('/nilai/create', [GuruNilaiController::class, 'create'])->name('guru.nilai.create');
    Route::post('/nilai', [GuruNilaiController::class, 'store'])->name('guru.nilai.store');
    Route::get('/nilai/{id}/edit', [GuruNilaiController::class, 'edit'])->name('guru.nilai.edit');
    Route::post('/nilai/{id}', [GuruNilaiController::class, 'update'])->name('guru.nilai.update');
    Route::delete('/nilai/{id}', [GuruNilaiController::class, 'destroy'])->name('guru.nilai.destroy');
});

/*
|--------------------------------------------------------------------------
| Siswa Routes
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->middleware('siswa')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');

    // View Nilai (read only)
    Route::get('/nilai', [SiswaNilaiController::class, 'index'])->name('siswa.nilai.index');
});

/*
|--------------------------------------------------------------------------
| Test route to check session (remove in production)
|--------------------------------------------------------------------------
*/
Route::get('/check-session', function () {
    echo '<pre>';
    echo 'Session Data:<br>';
    print_r(session()->all());
    echo '<br><br>';

    if (session()->has('user')) {
        echo 'User is logged in as: ' . session('user')->role . '<br>';
        echo 'User name: ' . session('user')->nama . '<br>';
        echo 'User email: ' . session('user')->email . '<br>';
    } else {
        echo 'No user in session';
    }
    echo '</pre>';
    exit;
});
