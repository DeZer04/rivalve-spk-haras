<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'user-access:karyawan'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'user-access:supervisor'])->group(function () {
    Route::get('/supervisor/home', [HomeController::class, 'supervisorHome'])->name('supervisor.home');
});

Route::middleware(['auth', 'user-access:hrd'])->group(function () {
    Route::get('/hrd/home', [HomeController::class, 'hrdHome'])->name('hrd.home');
});
