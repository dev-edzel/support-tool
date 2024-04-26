<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketInfoController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['prefix' => 'user'], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('logout', [AuthController::class, 'logout']);
})->middleware('auth.jwt');

Route::group(['prefix' => 'tickets/trashed'], function () {
    Route::get('', [TicketController::class, 'trashed']);
    Route::get('/restore/{id}', [TicketController::class, 'restore']);
});
Route::resource('tickets', TicketController::class);

Route::group(['prefix' => 'ticket-info/trashed'], function () {
    Route::get('', [TicketInfoController::class, 'trashed']);
    Route::get('/restore/{id}', [TicketInfoController::class, 'restore']);
});
Route::resource('ticket-info', TicketInfoController::class);

Route::group(['prefix' => 'ticket-type/trashed'], function () {
    Route::get('', [TicketTypeController::class, 'trashed']);
    Route::get('/restore/{id}', [TicketTypeController::class, 'restore']);
});
Route::resource('ticket-type', TicketTypeController::class);

Route::group(['prefix' => 'categories/trashed'], function () {
    Route::get('', [CategoryController::class, 'trashed']);
    Route::get('/restore/{id}', [CategoryController::class, 'restore']);
});
Route::resource('categories', CategoryController::class);

Route::group(['prefix' => 'sub-categories/trashed'], function () {
    Route::get('', [SubCategoryController::class, 'trashed']);
    Route::get('/restore/{id}', [SubCategoryController::class, 'restore']);
});
Route::resource('sub-categories', SubCategoryController::class);
