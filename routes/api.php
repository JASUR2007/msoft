<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseRefundController;
use App\Http\Controllers\SalesRefundController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

Route::post('batches', [BatchController::class, 'store']);
Route::get('/products/available', [ProductController::class, 'index']);
Route::post('/batch/{batch}/refunds', [PurchaseRefundController::class, 'store']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('storage/stock', [StorageController::class, 'index']);
Route::get('batches/profits', [BatchController::class, 'profits']);
Route::post('orders/{order}/refunds', [SalesRefundController::class, 'store']);
