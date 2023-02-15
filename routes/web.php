<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactInfoTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Categories
Route::resource('categories', CategoryController::class);
// Contact info types
Route::resource('contactInfoTypes', ContactInfoTypeController::class);

// Users
Route::controller(UsersController::class)->group(function () {
    Route::get('users', 'index')->name('users.index');
    Route::get('users/{id}/transaction', 'transaction')->name('users.transaction');
    Route::post('users/{id}/deposit', 'deposit')->name('users.deposit');
});

Auth::routes();
