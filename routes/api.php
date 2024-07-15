<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// http://localhost:8000/api/
// univseral resource locator
// tickets
// users

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('tickets', TicketController::class)->except(['update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

    Route::apiResource('authors', AuthorsController::class);
    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except(['update']);
    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
