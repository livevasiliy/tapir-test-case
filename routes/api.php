<?php

use App\Http\Controllers\CreateOrderAction;
use App\Http\Controllers\RunImportVehicleAction;
use App\Http\Controllers\StockAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/import-vehicles', RunImportVehicleAction::class);
Route::get('/stock', StockAction::class);
Route::post('/order', CreateOrderAction::class);
