<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\DetailEventController;
use App\Http\Controllers\EventParticipantController;
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
    Route::resource("cabang", CabangController::class)->middleware('auth');
    Route::resource("golongan", GolonganController::class)->middleware('auth');
    Route::resource('event', DetailEventController::class)->middleware('auth');
    Route::get('/get-golongan/{cabang_id}', [GolonganController::class, 'getGolonganByCabang']);
    Route::get('/home/event/{slug}', [HomeController::class, 'event'])->name('home.event');
    Route::get('/home/select-event/{id}', [HomeController::class, 'selectEvent'])->name('home.select_event');
    Route::resource('operator', OperatorController::class);

    // Resource untuk pengelolaan peserta (user role: peserta)
    Route::resource('peserta', PesertaController::class);
    // Routes untuk verifikasi dan upload berkas event participants
    Route::get('/event-participant/{participant}/upload', [\App\Http\Controllers\EventParticipantController::class, 'uploadForm'])->name('event-participant.upload.form');
    Route::post('/event-participant/{participant}/upload', [\App\Http\Controllers\EventParticipantController::class, 'upload'])->name('event-participant.upload');
    Route::post('/event-participant/{participant}/verify', [\App\Http\Controllers\EventParticipantController::class, 'verify'])->name('event-participant.verify');
    Route::post('/event-participant/{participant}/reject', [\App\Http\Controllers\EventParticipantController::class, 'reject'])->name('event-participant.reject');
});

