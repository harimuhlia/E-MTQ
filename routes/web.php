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
    // Dashboard home & event selection
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Event dashboard and selecting event are limited to administrators and operator (admin_desa)
    Route::middleware('role:administrator,admin_desa')->group(function () {
        Route::get('/home/event/{slug}', [HomeController::class, 'event'])->name('home.event');
        Route::get('/home/select-event/{id}', [HomeController::class, 'selectEvent'])->name('home.select_event');
    });

    // Routes accessible only to superadmin (administrator)
    Route::middleware('role:administrator')->group(function () {
        Route::resource('desa', DesaController::class);
        Route::resource('cabang', CabangController::class);
        Route::resource('golongan', GolonganController::class);
        Route::resource('event', DetailEventController::class);
        Route::resource('operator', OperatorController::class);
        // Pengumuman: hanya superadmin dapat menambah/mengubah/menghapus
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
    });

    // Routes accessible to superadmin and admin desa (pendaftaran & verifikasi)
    Route::middleware('role:administrator,admin_desa')->group(function () {
        // Form daftar peserta, simpan peserta, dll.
        Route::resource('peserta', PesertaController::class)->except(['index', 'show']);
        // Endpoint untuk mendapatkan golongan berdasarkan cabang
        Route::get('/get-golongan/{cabang_id}', [GolonganController::class, 'getGolonganByCabang']);
        // Event participants verification & upload
        Route::get('/event-participant/{participant}/upload', [EventParticipantController::class, 'uploadForm'])->name('event-participant.upload.form');
        Route::post('/event-participant/{participant}/upload', [EventParticipantController::class, 'upload'])->name('event-participant.upload');
        Route::post('/event-participant/{participant}/verify', [EventParticipantController::class, 'verify'])->name('event-participant.verify');
        Route::post('/event-participant/{participant}/reject', [EventParticipantController::class, 'reject'])->name('event-participant.reject');
    });

    // Routes accessible to all authenticated users
    // Peserta index: semua peran dapat melihat daftar peserta
    Route::get('peserta', [PesertaController::class, 'index'])->name('peserta.index');
    // Announcements index & show: semua dapat melihat
    Route::get('announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
});

