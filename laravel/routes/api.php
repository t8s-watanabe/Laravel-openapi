
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DefaultUserController;
use App\Http\Controllers\Api\OpenApiUserController;

Route::prefix('default')->group(function () {
    Route::apiResource('/users', DefaultUserController::class);
});

Route::prefix('custom')->group(function () {
    Route::apiResource('/users', OpenApiUserController::class);
});
