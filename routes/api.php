<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [App\Http\Controllers\AuthController::class, 'me']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('/dashboard/statistics', [App\Http\Controllers\DashboardController::class, 'getStatistics']);

    Route::get('/ticket', [App\Http\Controllers\TicketController::class, 'index']);
    Route::get('/ticket/{code}', [App\Http\Controllers\TicketController::class, 'show']);
    Route::post('/ticket', [App\Http\Controllers\TicketController::class, 'store']);
    Route::post('/ticket-reply/{code}', [App\Http\Controllers\TicketController::class, 'storeReply']);
});