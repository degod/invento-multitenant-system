<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{id}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{id}', [TenantController::class, 'update'])->name('tenants.update');
        Route::delete('/tenants/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');
    });
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');

    Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');
    Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store');
    Route::get('/buildings/{id}/edit', [BuildingController::class, 'edit'])->name('buildings.edit');
    Route::put('/buildings/{id}', [BuildingController::class, 'update'])->name('buildings.update');
    Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('buildings.destroy');

    Route::get('/flats', [FlatController::class, 'index'])->name('flats.index');
});
