<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\ConsumptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReceptionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function(){
        Route::get('/',[ProductController::class,'index'])->name('home');
        Route::put('/',[ProductController::class,'index'])->name('home');
        Route::get('/product',[ProductController::class,'index'])->name('product');
        Route::get('/consumption',[ConsumptionController::class,'index'])->name('consumption');
        Route::resource('/crudconsumption', ConsumptionController::class);
        Route::resource('/crudproduct',ProductController::class);
        Route::resource('/crudreception',ReceptionController::class);
        Route::get('/reception',[ReceptionController::class,'index'])->name('reception');
        Route::get('consumption/{product}/product', [ConsumptionController::class, 'getConsumptionsByProduct'])->name('consumption.product');
        Route::get('order/{column}/{direction}/consumption', [ConsumptionController::class, 'getConsumptionsByOrder'])->name('order.consumption');
        Route::get('reception/{product}/product', [ReceptionController::class, 'getReceptionsByProduct'])->name('reception.product');
        Route::get('order/{column}/{direction}/reception', [ReceptionController::class, 'getReceptionsByOrder'])->name('order.reception');
        Route::get('product/{type}', [ProductController::class, 'getProductsByType'])->name('product.type');
        Route::delete('/deleteconsumption', [ConsumptionController::class, 'index'])->name('consumption.delete');
        Route::delete('/product', [ProductController::class, 'index'])->name('deleteproduct');
        Route::delete('/reception', [ReceptionController::class, 'index'])->name('deletereception');
        Route::put('/consumption',[ConsumptionController::class,'index'])->name('consumption.put');
        Route::put('/reception',[ReceptionController::class,'index'])->name('reception.put');
        Route::put('/product',[ProductController::class,'index'])->name('product.put');
        Route::get('serch',[ConsumptionController::class,'search'])->name('search');
        Route::post('user/logout',[Auth::class,'logout'])->name('logout');
        Route::get('user/login',[Auth::class,'login'])->name('login');
        Route::resource('command',CommandController::class);
        Route::delete('command',[CommandController::class,'index'])->name('command.delete');
        Route::put('command',[CommandController::class,'index'])->name('command.index');
        Route::get('statistic',[ConsumptionController::class,'showChart'])->name('statistic');
        Route::get('users',[Auth::class,'index'])->name('users');
        Route::delete('users',[Auth::class,'index'])->name('users');
        Route::resource('user',Auth::class);
        Route::get('user/profile/{id}',[Auth::class,'show'])->name('profile');
        Route::put('user/profile/{id}',[Auth::class,'show'])->name('profile');
});

Route::middleware('guest')->group(function(){
        Route::get('user/login',[Auth::class,'login'])->name('login');
        Route::post('user/login',[Auth::class,'auth'])->name('login');
        Route::get('forgot_password',[Auth::class,'forgot_password'])->name('forgot');
        Route::post('forgot_password',[Auth::class,'verify_email'])->name('verify_email');
        Route::get('password/reset/{token}', [Auth::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [Auth::class, 'reset'])->name('password_update');
});

