<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\KaryawanChatController;

// ROOT
Route::get('/', function () {
    return redirect('/login');
});

// TEMPORARY SETUP ROUTE (Remove after use)
Route::get('/setup', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return "Setup berhasil! Database telah direset (migrate:fresh) dan Data Dummy telah di-generate ulang dengan 4 angka. Silakan login.";
    } catch (\Exception $e) {
        return "Terjadi kesalahan: " . $e->getMessage();
    }
});

// LOGIN ADMIN & KARYAWAN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// LOGIN CLIENT (EMPLOYEE)
Route::get('/employee', [AuthController::class, 'employeeForm'])->name('employee.login');
Route::post('/employee', [AuthController::class, 'employeeLogin']);


// -----------------------------------------
// ROUTE ADMIN
// -----------------------------------------
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [CounselorController::class, 'adminDashboard']);
    Route::get('/admin/monitoring', [CounselorController::class, 'monitoring']);
    Route::get('/admin/statistik', [CounselorController::class, 'statistik']);
    Route::get('/admin/laporan', [CounselorController::class, 'laporan']);
    Route::get('/admin/laporan/export', [CounselorController::class, 'exportLaporan']);
    Route::get('/admin/laporan/export-pdf', [CounselorController::class, 'exportPdfLaporan']);
    
    // Admin hanya melihat (read-only monitoring penuh - summary only)
    Route::get('/admin/chat', [AdminChatController::class, 'index']);
});


// -----------------------------------------
// ROUTE KARYAWAN (KONSELOR)
// -----------------------------------------
Route::middleware(['role:karyawan'])->group(function () {
    Route::get('/karyawan/dashboard', [CounselorController::class, 'karyawanDashboard']);
    
    // Karyawan menangani chat
    Route::get('/karyawan/chat', [KaryawanChatController::class, 'index']);
    Route::get('/karyawan/chat/{id}', [KaryawanChatController::class, 'show']);
    Route::post('/karyawan/chat/{id}', [KaryawanChatController::class, 'reply']);
    Route::post('/karyawan/chat/{id}/status', [KaryawanChatController::class, 'updateStatus']);
    Route::get('/karyawan/chat/messages/{id}', [KaryawanChatController::class, 'messages']);
});


// -----------------------------------------
// ROUTE CLIENT (EMPLOYEE)
// -----------------------------------------
Route::middleware(['role:client'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard']);
    Route::get('/employee/mood', [EmployeeController::class, 'mood']);
    Route::post('/employee/mood', [EmployeeController::class, 'saveMood']);
    Route::get('/employee/riwayat-mood', [EmployeeController::class, 'riwayatMood']);
    
    // Chat konseling user
    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'send'])->middleware('throttle:20,1');
    Route::get('/chat/messages/{id}', [ChatController::class, 'messages']);
});