<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
<<<<<<< HEAD
=======
Route::get('/', fn() => view('welcome'))->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/publikasi', 'publikasi')->name('publikasi.index');
Route::view('/konselor', 'konselor')->name('konselor.index');
Route::view('/inbox', 'inbox')->name('inbox');
Route::view('/konseling', 'konseling')->name('konseling.create');
>>>>>>> autentikasi

Route::get('/', function () {
    return view('welcome');
});
<<<<<<< HEAD
=======

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
>>>>>>> autentikasi
