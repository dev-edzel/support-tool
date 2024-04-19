<?php

use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('tickets', TicketController::class);
Route::group(['prefix' => 'tickets/trashed'], function () {
    Route::get('', [TicketController::class, 'trashed']);
    Route::get('/restore/{id}', [TicketController::class, 'restore']);
});
