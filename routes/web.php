<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

//tester tanpa koneksi kalbe (untuk sementara)
// Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/auth', [App\Http\Controllers\Auth\HrisController::class, 'store'])->name('loginHris'); 

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->middleware('NetworkTesting')->name('login');
Route::post('/auth', [App\Http\Controllers\Auth\HrisController::class, 'store'])->middleware('NetworkTesting')->name('loginHris');

Route::get('/logout', [App\Http\Controllers\Auth\HrisController::class, 'logout'])->name('logout');

Route::post('/clear-notifications', [App\Http\Controllers\System\NotificationController::class, 'clear']);
Route::post('/read-notifications', [App\Http\Controllers\System\NotificationController::class, 'clear']);

require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
