<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictionController;

Route::get('/', function () {
    return redirect()->route('predictions.index');
});

Route::resource('predictions', PredictionController::class)
    ->only(['index', 'create', 'store', 'show']);