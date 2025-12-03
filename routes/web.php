<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Http\Controllers\MasterSatkerController;
use App\Http\Controllers\MasterJenisPengelolaanController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ReportController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Satker
    Route::prefix('master/satker')->name('master.satker.')->group(function () {
        // Read-only for Staff & Admin
        Route::get('/', [MasterSatkerController::class, 'index'])
            ->middleware('role:admin,staff')
            ->name('index');

        // Write access for Admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [MasterSatkerController::class, 'create'])->name('create');
            Route::post('/', [MasterSatkerController::class, 'store'])->name('store');
            Route::get('/{satker}/edit', [MasterSatkerController::class, 'edit'])->name('edit');
            Route::put('/{satker}', [MasterSatkerController::class, 'update'])->name('update');
            Route::delete('/{satker}', [MasterSatkerController::class, 'destroy'])->name('destroy');
        });
    });

    // Master Jenis Pengelolaan
    Route::prefix('master/jenis-pengelolaan')->name('master.jenis-pengelolaan.')->group(function () {
        // Read-only for Staff & Admin
        Route::get('/', [MasterJenisPengelolaanController::class, 'index'])
            ->middleware('role:admin,staff')
            ->name('index');

        // Write access for Admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [MasterJenisPengelolaanController::class, 'create'])->name('create');
            Route::post('/', [MasterJenisPengelolaanController::class, 'store'])->name('store');
            Route::get('/{jenisPengelolaan}/edit', [MasterJenisPengelolaanController::class, 'edit'])->name('edit');
            Route::put('/{jenisPengelolaan}', [MasterJenisPengelolaanController::class, 'update'])->name('update');
            Route::delete('/{jenisPengelolaan}', [MasterJenisPengelolaanController::class, 'destroy'])->name('destroy');
        });
    });
    // Master User
    Route::resource('master/users', MasterUserController::class)
        ->names('master.users')
        ->middleware('role:admin');

    // Agenda
    Route::resource('agenda', AgendaController::class)
        ->middleware('role:admin,staff');
    Route::post('agenda/{agenda}/update-status', [AgendaController::class, 'updateStatus'])
        ->name('agenda.update-status')
        ->middleware('role:admin,staff');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daftar-agenda', [ReportController::class, 'daftarAgenda'])->name('daftar-agenda');
        Route::get('/daftar-agenda/pdf', [ReportController::class, 'daftarAgendaPdf'])->name('daftar-agenda.pdf');
        Route::get('/daftar-agenda/excel', [ReportController::class, 'daftarAgendaExcel'])->name('daftar-agenda.excel');

        Route::get('/status-persetujuan', [ReportController::class, 'statusPersetujuan'])->name('status-persetujuan');
        Route::get('/status-persetujuan/pdf', [ReportController::class, 'statusPersetujuanPdf'])->name('status-persetujuan.pdf');
        Route::get('/status-persetujuan/excel', [ReportController::class, 'statusPersetujuanExcel'])->name('status-persetujuan.excel');

        Route::get('/durasi-proses', [ReportController::class, 'durasiProses'])->name('durasi-proses');
        Route::get('/durasi-proses/pdf', [ReportController::class, 'durasiProsesPdf'])->name('durasi-proses.pdf');
        Route::get('/durasi-proses/excel', [ReportController::class, 'durasiProsesExcel'])->name('durasi-proses.excel');

        Route::get('/performance-satker', [ReportController::class, 'performanceSatker'])->name('performance-satker');
        Route::get('/performance-satker/pdf', [ReportController::class, 'performanceSatkerPdf'])->name('performance-satker.pdf');
        Route::get('/performance-satker/excel', [ReportController::class, 'performanceSatkerExcel'])->name('performance-satker.excel');

        Route::get('/summary-bulanan', [ReportController::class, 'summaryBulanan'])->name('summary-bulanan');
        Route::get('/summary-bulanan/pdf', [ReportController::class, 'summaryBulananPdf'])->name('summary-bulanan.pdf');
        Route::get('/summary-bulanan/excel', [ReportController::class, 'summaryBulananExcel'])->name('summary-bulanan.excel');
    });
});

require __DIR__ . '/auth.php';
