<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerdinController;
use App\Http\Controllers\AdminController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [PerdinController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [PerdinController::class, 'dashboard']);

    Route::get('/perdin/tambah', [PerdinController::class, 'tambahForm'])->name('perdin.tambah');
    Route::post('/perdin/tambah', [PerdinController::class, 'tambahStore']);

    Route::post('/perdin/{id}/approve', [PerdinController::class, 'approve'])->name('perdin.approve');
    Route::post('/perdin/{id}/reject', [PerdinController::class, 'reject'])->name('perdin.reject');

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/users/{id}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.storeUser');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::post('/admin/kota', [AdminController::class, 'storeKota'])->name('admin.storeKota');
    Route::put('/admin/kota/{id}', [AdminController::class, 'updateKota'])->name('admin.updateKota');
    Route::delete('/admin/kota/{id}', [AdminController::class, 'deleteKota'])->name('admin.deleteKota');
    Route::delete('/admin/perdin/{id}', [AdminController::class, 'deletePerdin'])->name('admin.deletePerdin');
    Route::put('/admin/perdin/{id}/status', [AdminController::class, 'updatePerdinStatus'])->name('admin.updatePerdinStatus');
});
