<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'index']);
