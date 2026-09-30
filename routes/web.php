<?php

declare(strict_types=1);

use App\Http\Controllers\ImportLeadController;
use App\Http\Controllers\ShowImportFormController;
use Illuminate\Support\Facades\Route;

Route::get('/import', ShowImportFormController::class)->name('import.form');
Route::post('/import', ImportLeadController::class)->name('import.process');
