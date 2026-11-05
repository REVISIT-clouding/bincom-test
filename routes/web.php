
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PollingUnitController;
use App\Http\Controllers\CompareResultController;
use App\Http\Controllers\StoreResultsController;

Route::get('/', function () {
    return redirect()->route('polling-units');
});

Route::get('/dashboard', [PollingUnitController::class, 'index'])->name('polling-units');

Route::get('/results', [CompareResultController::class, 'index'])->name('results');

Route::get('/create-result', [StoreResultsController::class, 'create']);

Route::post('/api/save-pu-results', [StoreResultsController::class, 'store']);
