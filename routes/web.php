<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PublikasiController;

// Konsuli Controllers
use App\Http\Controllers\Konsuli\KonselingController;
use App\Http\Controllers\Konsuli\PublikasiController as KonsuliPublikasiController;

// Konselor Controllers
use App\Http\Controllers\Konselor\KonselingSessionController;
use App\Http\Controllers\Konselor\LaporanController as KonselorLaporanController;
use App\Http\Controllers\Konselor\PublikasiController as KonselorPublikasiController;
use App\Http\Controllers\Konselor\DashboardController as KonselorDashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PublikasiController as AdminPublikasiController;
use App\Http\Controllers\Admin\KelolaPenggunaController as AdminKelolaPenggunaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
/*
|--------------------------------------------------------------------------
| PUBLIC (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');
Route::view('/tentang', 'tentang')->name('tentang');

Route::get('/publikasi', [PublikasiController::class, 'index'])->name('guest.publikasi.index');
Route::get('/publikasi/{publikasi:slug}', [PublikasiController::class, 'show'])->name('guest.publikasi.show');

// Guest pages (static)
Route::view('/konselor', 'konselor')->name('guest.konselor');
Route::view('/konseling', 'konseling')->name('guest.konseling');

// LAPORAN GUEST
Route::get('/laporan', [LaporanController::class, 'guestIndex'])->name('guest.laporan.index');
Route::get('/laporan/create', [LaporanController::class, 'guestCreate'])->name('guest.laporan.create');
Route::post('/laporan', [LaporanController::class, 'guestStore'])->name('guest.laporan.store');

/*
|--------------------------------------------------------------------------
| REDIRECT SETELAH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/redirect-by-role', function () {
    return match (auth()->user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'konselor' => redirect()->route('konselor.dashboard'),
        'konsuli'  => redirect()->route('konsuli.dashboard'),
        default    => abort(403),
    };
})->name('redirect.by.role');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard',[AdminDashboardController::class, 'index'])->name('dashboard');

        // Laporan
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{laporan}', [AdminLaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/cetak/pdf/{id}', [AdminLaporanController::class, 'cetakPdf'])->name('laporan.cetak.pdf');

        // Publikasi
        Route::resource('publikasi', AdminPublikasiController::class);
        Route::post('publikasi/upload-thumbnail', [AdminPublikasiController::class, 'uploadThumbnailAjax'])
            ->name('publikasi.upload-thumbnail');

        // Kelola Pengguna
        Route::prefix('kelola_pengguna')->name('kelola_pengguna.')->group(function () {
            Route::get('/', [AdminKelolaPenggunaController::class, 'index'])->name('index');
            Route::get('/create', [AdminKelolaPenggunaController::class, 'create'])->name('create');
            Route::post('/', [AdminKelolaPenggunaController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [AdminKelolaPenggunaController::class, 'edit'])->name('edit');
            Route::put('/{user}', [AdminKelolaPenggunaController::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminKelolaPenggunaController::class, 'destroy'])->name('destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| KONSELOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:konselor'])
    ->prefix('konselor')->name('konselor.')
    ->group(function () {

        //Route::view('/dashboard', 'konselor.dashboard')->name('dashboard');
        Route::get('/dashboard', [KonselorDashboardController::class, 'index'])->name('dashboard');
        
        // Publikasi
        Route::resource('publikasi', KonselorPublikasiController::class);
        Route::post('publikasi/upload-thumbnail', [KonselorPublikasiController::class, 'uploadThumbnailAjax'])
            ->name('publikasi.upload-thumbnail');

        // Laporan
        Route::get('/laporan', [KonselorLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{laporan}', [KonselorLaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/cetak/pdf/{id}', [KonselorLaporanController::class, 'cetakPdf'])->name('laporan.cetak.pdf');

        // Konseling - Konselor Side
        Route::prefix('konseling')->name('konseling.')->group(function () {
            Route::get('/', [KonselingSessionController::class, 'index'])->name('index');
            Route::post('/{session}/approve', [KonselingSessionController::class, 'approve'])->name('approve');
            Route::post('/{session}/reject', [KonselingSessionController::class, 'reject'])->name('reject');
            Route::get('/{session}/chat', [KonselingSessionController::class, 'chat'])->name('chat');
            Route::post('/{session}/send', [KonselingSessionController::class, 'sendMessage'])->name('send');
            Route::get('/{session}/messages', [KonselingSessionController::class, 'getMessages'])->name('messages');
            Route::get('/{session}/end-form', [KonselingSessionController::class, 'showEndSessionForm'])->name('end-form');
            Route::post('/{session}/end', [KonselingSessionController::class, 'endSession'])->name('end');
            Route::get('/{session}/detail', [KonselingSessionController::class, 'showDetail'])->name('detail');
        });
    });

/*
|--------------------------------------------------------------------------
| KONSULI (Konsuli)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:konsuli'])
    ->prefix('konsuli')->name('konsuli.')
    ->group(function () {

        Route::view('/dashboard', 'konsuli.dashboard')->name('dashboard');
        Route::view('/tentang', 'konsuli.tentang')->name('tentang');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'konsuliIndex'])->name('laporan.index');
        Route::get('/laporan/create', [LaporanController::class, 'konsuliCreate'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'konsuliStore'])->name('laporan.store');

        // Publikasi
        Route::get('/publikasi', [KonsuliPublikasiController::class, 'index'])->name('publikasi.index');
        Route::get('/publikasi/{publikasi:slug}', [KonsuliPublikasiController::class, 'show'])->name('publikasi.show');

        // 🔥 MENU KONSELOR - Daftar Konselor untuk Dipilih
        Route::get('/konselor', [KonselingController::class, 'daftarKonselor'])->name('konselor.index');

        // 🔥 MENU KONSELING - Sesi Konseling Konsuli
        Route::prefix('konseling')->name('konseling.')->group(function () {
            Route::get('/', [KonselingController::class, 'index'])->name('index');
            Route::post('/request', [KonselingController::class, 'request'])->name('request');
            Route::get('/{session}/chat', [KonselingController::class, 'chat'])->name('chat');
            Route::post('/{session}/send', [KonselingController::class, 'sendMessage'])->name('send');
            Route::get('/{session}/messages', [KonselingController::class, 'getMessages'])->name('messages');
            Route::post('/{session}/end', [KonselingController::class, 'endSession'])->name('end');
        });
    });

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';