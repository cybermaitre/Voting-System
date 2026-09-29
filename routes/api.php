<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PollController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/polls/{slug}', [PollController::class, 'show']);
Route::post('/polls/{slug}/vote', [PollController::class, 'vote']);
Route::get('/polls/{slug}/stream', [PollController::class, 'stream']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/polls', [PollController::class, 'store']);
});