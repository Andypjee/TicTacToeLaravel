<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MoveController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('players', PlayerController::class);
Route::resource('moves', MoveController::class);

