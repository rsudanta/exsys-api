<?php

use App\Http\Controllers\BasisPengetahuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')
    ->middleware('auth:sanctum', 'admin')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('penyakit', PenyakitController::class);
        Route::resource('gejala', GejalaController::class);
        Route::resource('rules', BasisPengetahuanController::class);
        Route::get('/search/user', [UserController::class, 'search'])->name('search_user');
        Route::get('/search/penyakit', [PenyakitController::class, 'search'])->name('search_penyakit');
        Route::get('/search/gejala', [GejalaController::class, 'search'])->name('search_gejala');
    });
