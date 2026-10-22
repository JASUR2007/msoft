<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

Route::post('batches', [BatchController::class, 'store']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('storage/stock', [StorageController::class, 'index']);
