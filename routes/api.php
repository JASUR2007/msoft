<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('batches', [BatchController::class, 'store']);
Route::post('orders', [OrderController::class, 'store']);
