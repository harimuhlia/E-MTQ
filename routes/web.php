<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\KetentuanUsiaController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\HomeController;
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
Route::middleware(['auth'])->group(function () {
    Route::resource("desa", DesaController::class)->middleware(['auth']);
    Route::resource("tahunevent", TahuneventController::class)->middleware('auth');
    Route::resource("cabang", CabangController::class)->middleware('auth');
    Route::resource("golongan", GolonganController::class)->middleware('auth');
    // Route untuk mendapatkan daftar golongan berdasarkan cabang secara AJAX.
    // Fitur ketentuan usia kini diintegrasikan dalam tabel golongan, sehingga resource ketentuanusia dihapus.
    Route::get('/get-golongan/{cabang_id}', [KetentuanUsiaController::class, 'getGolonganByCabang']);

    // Event selection and event-specific dashboard
    Route::get('/home/event/{slug}', [HomeController::class, 'event'])->name('home.event');
    Route::get('/home/select-event/{id}', [HomeController::class, 'selectEvent'])->name('home.select_event');

    // Resource untuk pengelolaan operator desa (role admin_desa)
    Route::resource('operator', OperatorController::class);
});

Route::get('pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('auth');
Route::post('pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('auth');