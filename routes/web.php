<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunBiayaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NamaBarangController;
use App\Http\Controllers\NamaKaryawanController;


Route::middleware('guest')->group(function () {
    
    // Form login
    Route::get('/login', [LoginController::class, 'login'])
         ->name('login');

    // Proses login
    Route::post('/login', [LoginController::class, 'posts'])
         ->middleware('log.sensitive')
         ->name('login.submit');
});

// Logout (method POST demi keamanan; pakai @csrf di form logout)
Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware(['auth', 'log.sensitive'])
     ->name('logout');



// Profile routes untuk direktur & user, tetap pakai log.sensitive
Route::middleware(['auth', 'log.sensitive'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// auth direktur
Route::middleware(['auth', 'role:direktur', 'log.sensitive'])
    ->prefix('direktur')
    ->name('direktur.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'direkturDashboard'])->name('dashboard');
        Route::resource('users', UserController::class);
        // Audit Log routes
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/audit/{auditLog}', [AuditLogController::class, 'show'])->name('audit.show');
        Route::post('/audit/export', [AuditLogController::class, 'export'])->name('audit.export');
        // Tambahkan resource lain untuk direktur jika diperlukan
    });

// auth keuangan
Route::middleware(['auth', 'role:keuangan', 'log.sensitive'])
    ->prefix('keuangan')
    ->name('keuangan.')
    ->group(function () {
        //-------------------------------- DASHBOARD -------------------------------//
        Route::get('/dashboard', [DashboardController::class, 'keuanganDashboard'])->name('dashboard');

        //-------------------------------- DATABASE -------------------------------//
        Route::resource('barangs', NamaBarangController::class);
        Route::get('/database/karyawan', [NamaKaryawanController::class, 'index'])->name('database.karyawan');
        Route::get('/database/karyawan/sync', [NamaKaryawanController::class, 'sync'])->name('database.karyawan.sync');
        Route::get('/database/plot', [AkunBiayaController::class, 'index'])->name('database.plot');
        Route::get('/database/plot/sync', [AkunBiayaController::class, 'sync'])->name('database.plot.sync');
        
    });

Route::redirect('/', '/login');
