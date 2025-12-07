<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bahan-baku', \App\Http\Controllers\BahanBakuController::class);
    Route::resource('produk', \App\Http\Controllers\ProdukController::class);
    Route::resource('topping', \App\Http\Controllers\ToppingController::class);

    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [\App\Http\Controllers\PosController::class, 'store'])->name('pos.store');
    Route::post('/pos/pay/{id}', [\App\Http\Controllers\PosController::class, 'pay'])->name('pos.pay');
    Route::post('/pos/status/{id}', [\App\Http\Controllers\PosController::class, 'updateStatus'])->name('pos.updateStatus');
    Route::get('/kitchen', [\App\Http\Controllers\PosController::class, 'kitchen'])->name('kitchen.index');
    Route::post('/pos/print/{id}', [\App\Http\Controllers\PrinterController::class, 'printBill'])->name('pos.print');
    Route::post('/kitchen/{id}/complete', [\App\Http\Controllers\PosController::class, 'completeOrder'])->name('pos.complete');

    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [\App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/riwayat', [\App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}', [\App\Http\Controllers\RiwayatController::class, 'show'])->name('riwayat.show');
    Route::delete('/riwayat/{id}', [\App\Http\Controllers\RiwayatController::class, 'destroy'])->name('riwayat.destroy');

    Route::resource('users', \App\Http\Controllers\UserController::class);
});
