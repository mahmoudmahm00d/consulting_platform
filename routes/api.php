<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ContactInfoTypeController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SpecialistsController;
use App\Http\Controllers\Api\WalletController;
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
    // Schedule
    Route::get('account/schedule', 'schedule')->name('account.schedule');
    Route::post('account/schedule/add', 'addSchedule')->name('account.schedule.add');
    Route::post('account/schedule/{id}/delete', 'deleteSchedule')->name('account.schedule.delete');
    // User Profile
    Route::get('account/me', 'me')->name('account.me');
    Route::get('account/testing', 'testing')->name('account.testing');
    // Update info
    Route::post('account/me', 'update')->name('account.update');
    // Credentials
    Route::post('account/changeEmail', 'changeEmail')->name('account.email.update');
    Route::post('account/changePhone', 'changePhone')->name('account.phone.update');
    Route::post('account/changePassword', 'changePassword')->name('account.password.update');
});

Route::controller(SpecialistsController::class)->group(function () {
    Route::get('specialists', 'index')->name('specialists.index');
    Route::get('specialists/{id}', 'byId')->name('specialists.id');
    Route::get('specialists/byCategory/{id}', 'byCategory')->name('specialists.category');
    Route::post('specialists/schedule/{id}', 'schedule')->name('specialists.schedule');
    Route::post('specialists/rate', 'rate')->name('specialists.rate');
});

Route::controller(AppointmentController::class)->group(function () {
    Route::get('appointments', 'mine')->name('appointment.mine');
    Route::get('appointments/{id}', 'show')->name('appointment.show');
    Route::post('appointments/book', 'book')->name('appointment.book');
    Route::post('appointments/check', 'checkAppointment')->name('appointment.check');
});

Route::controller(WalletController::class)->group(function () {
    Route::get('wallet', 'amount')->name('wallet.amount');
    Route::get('wallet/transactions', 'transactions')->name('wallet.transaction');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('search', 'search')->name('search');
});

Route::controller(ContactInfoTypeController::class)->group(function () {
    Route::get('contactInfoTypes', 'index')->name('contactInfoType');
});
