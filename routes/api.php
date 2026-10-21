<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchController;

Route::post('batches', [BatchController::class, 'store']);
Route::get('/products/available', []);
Route::post('/batch/:id/refunds');
Route::post('/orders');
Route::get('storage/stock');
Route::get('batches/profits');
Route::get('orders/:id/refunds');



