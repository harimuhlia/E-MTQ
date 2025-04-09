<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\KetentuanusiaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TahuneventController;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource("desa", DesaController::class)->middleware(['auth']);
Route::resource("tahunevent", TahuneventController::class)->middleware('auth');
Route::resource("cabang", CabangController::class)->middleware('auth');
Route::resource("golongan", GolonganController::class)->middleware('auth');
Route::resource('ketentuanusia', KetentuanUsiaController::class)->middleware('auth');
Route::get('get-golongan/{cabang_id}', [KetentuanUsiaController::class, 'getGolonganByCabang'])->middleware('auth');

Route::get('pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('auth');
Route::post('pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('auth');