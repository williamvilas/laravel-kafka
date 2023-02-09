<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;

Route::prefix('log')->group(function () {

    Route::get('/{document}', [LogController::class, 'findByDocument']);
});
