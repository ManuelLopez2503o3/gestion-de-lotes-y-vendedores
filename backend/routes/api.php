<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\VendedorController;

// --- RUTAS PÚBLICAS ---
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// --- RUTAS PROTEGIDAS (Necesitan Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

     
    Route::apiResource('lotes', LoteController::class);

    
    Route::apiResource('vendedores', VendedorController::class);

    
    Route::get('/lotes/{id}/vendedores', [LoteController::class, 'vendedoresPorLote']);

     
    Route::post('/importar-vendedores/{id}', [LoteController::class, 'importarVendedores']);

     
    Route::delete('/vendedores/{id}', [VendedorController::class, 'destroy']);

     
    Route::put('/vendedores/{id}/quitar', [App\Http\Controllers\LoteController::class, 'quitarVendedor']);
    
    
    Route::put('/vendedores/{id}/reasignar', [App\Http\Controllers\LoteController::class, 'actualizarVendedor']);

});