<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| PUBLIC (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/publikasi', 'publikasi')->name('publikasi.index');
Route::view('/konselor', 'konselor')->name('konselor.index');
Route::view('/inbox', 'inbox')->name('inbox');
Route::view('/konseling', 'konseling')->name('konseling.create');

/*
|--------------------------------------------------------------------------
| REDIRECT OTOMATIS SETELAH LOGIN
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
| PROFILE (Semua User Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD PER ROLE 
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:konselor'])
    ->prefix('konselor')->name('konselor.')
    ->group(function () {
        Route::view('/dashboard', 'konselor.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:konsuli'])
    ->prefix('konsuli')->name('konsuli.')
    ->group(function () {
        Route::view('/dashboard', 'konsuli.dashboard')->name('dashboard');
        Route::view('/tentang', 'konsuli.tentang')->name('tentang');
        Route::view('/publikasi', 'konsuli.publikasi.index')->name('publikasi.index');
        Route::view('/konselor', 'konsuli.konselor.index')->name('konselor.index');
        Route::view('/inbox', 'konsuli.inbox')->name('inbox');
    });

/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/lapor/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/lapor', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/lapor', [LaporanController::class, 'index'])->name('laporan.index');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
