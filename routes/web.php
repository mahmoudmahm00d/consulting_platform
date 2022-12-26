<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactInfoController;
use App\Http\Controllers\ContactInfoTypeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ContactInfoType;
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
Route::get('users', [Controller::class, 'index']);

Auth::routes();
