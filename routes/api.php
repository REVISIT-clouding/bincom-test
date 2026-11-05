<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PollingUnitController;
use App\Http\Controllers\AnnounncedLgaResultsController;
use App\Http\Controllers\AnnounncedPuResultController;
use App\Http\Controllers\CompareResultController;
use App\Http\Controllers\StoreResultsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('polling_units', PollingUnitController::class);
Route::apiResource('results', AnnounncedLgaResultsController::class);
Route::apiResource('pre-compare', CompareResultController::class);
Route::apiResource('party', StoreResultsController::class);
Route::post('/save-pu-results', [StoreResultsController::class, 'store']);