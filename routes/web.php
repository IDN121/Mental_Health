<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminChatController;

// ROOT
Route::get('/', function () {
    return redirect('/login');
});

// LOGIN ADMIN
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// EMPLOYEE LOGIN
Route::get('/employee', [AuthController::class, 'employeeForm']);
Route::post('/employee', [AuthController::class, 'employeeLogin']);
Route::get('/mood', [EmployeeController::class, 'mood']);

// DASHBOARD
Route::get('/dashboard', [CounselorController::class, 'dashboard']);
Route::get('/admin/monitoring', [CounselorController::class, 'monitoring']);
Route::get('/admin/statistik', [CounselorController::class, 'statistik']);
Route::get('/admin/laporan', [CounselorController::class, 'laporan']);
Route::get('/admin/laporan/export', [CounselorController::class, 'exportLaporan']);
Route::get('/admin/laporan/export-pdf', [CounselorController::class, 'exportPdfLaporan']);
Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard']);

// CHAT (FIX FINAL)
Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'send']);

// API
Route::get('/chat/messages/{id}', [ChatController::class, 'messages']);
Route::get('/admin/chat/messages/{id}', [AdminChatController::class, 'messages']);

// ADMIN CHAT
Route::get('/admin/chat', [AdminChatController::class, 'index']);
Route::get('/admin/chat/{id}', [AdminChatController::class, 'show']);
Route::post('/admin/chat/{id}', [AdminChatController::class, 'reply']);