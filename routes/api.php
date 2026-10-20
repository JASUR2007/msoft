<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchController;

Route::post('batches', [BatchController::class, 'index']);
Route::get('/products/available', []);
