<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // KASIR & ALL (Pos, Kitchen, Riwayat)
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // AKSES POS (Kasir & Admin)
    Route::middleware('role:KASIR,ADMIN')->group(function() {
        Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
        Route::post('/pos', [\App\Http\Controllers\PosController::class, 'store'])->name('pos.store');
        Route::post('/pos/pay/{id}', [\App\Http\Controllers\PosController::class, 'pay'])->name('pos.pay');
        Route::post('/pos/status/{id}', [\App\Http\Controllers\PosController::class, 'updateStatus'])->name('pos.updateStatus');
        Route::post('/pos/print/{id}', [\App\Http\Controllers\PrinterController::class, 'printBill'])->name('pos.print');
        
        Route::get('/riwayat', [\App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{id}', [\App\Http\Controllers\RiwayatController::class, 'show'])->name('riwayat.show');
    });

    // AKSES DAPUR (Dapur & Admin)
    Route::middleware('role:DAPUR,ADMIN')->group(function() {
        Route::get('/kitchen', [\App\Http\Controllers\PosController::class, 'kitchen'])->name('kitchen.index');
        Route::post('/kitchen/{id}/complete', [\App\Http\Controllers\PosController::class, 'completeOrder'])->name('pos.complete');
    });
    
    // ADMIN ONLY ROUTES
    Route::middleware('role:ADMIN')->group(function () {
        Route::resource('bahan-baku', \App\Http\Controllers\BahanBakuController::class);
        Route::resource('produk', \App\Http\Controllers\ProdukController::class);
        Route::resource('topping', \App\Http\Controllers\ToppingController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        
        Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [\App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
        Route::delete('/laporan/{id}', [\App\Http\Controllers\LaporanController::class, 'destroy'])->name('laporan.destroy');
        
        Route::delete('/riwayat/{id}', [\App\Http\Controllers\RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    });
});
