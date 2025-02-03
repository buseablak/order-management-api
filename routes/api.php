<?php

use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/create_order', [OrderController::class,'store']);
Route::get('/order', [OrderController::class,'index']);
Route::get('/order/{id}', [OrderController::class,'show']);
Route::get('/order_delete/{id}', [OrderController::class,'destroy']);
Route::get('/order_discount/{id}', [DiscountController::class,'discount'])->where('id', '[0-9]+');
