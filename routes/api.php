<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login')->name('auth.login');
    Route::post('auth/register', 'register')->name('auth.register');
    Route::get('auth/refresh', 'refresh')->name('auth.refresh');
});

Route::controller(CategoriesController::class)->group(function () {
    Route::get('categories', 'index')->name('categories.index');
});

Route::controller(AccountController::class)->group(function () {
    // Specializes
    Route::post('account/specializes/add', 'addSpecializes')->name('account.specialize.add');
    Route::post('account/specializes/{id}/delete', 'deleteSpecialize')->name('account.specialize.delete');
    // Contacts
    Route::post('account/contacts/add', 'addContacts')->name('account.contacts.add');
    Route::post('account/contacts/{id}/delete', 'deleteContact')->name('account.contacts.delete');
    // Upload image
    Route::post('account/image', 'uploadImage')->name('account.image.upload');
    // User Profile
    Route::get('account/me', 'me')->name('account.me');
    // Update info
    Route::post('account/me', 'update')->name('account.update');
    // Credentials
    Route::post('account/changeEmail', 'changeEmail')->name('account.email.update');
    Route::post('account/changePhone', 'changePhone')->name('account.phone.update');
    Route::post('account/changePassword', 'changePassword')->name('account.password.update');
});
