<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('news', NewsController::class);
    Route::post('news/{id}/moderate', [NewsController::class, 'moderate']);
});