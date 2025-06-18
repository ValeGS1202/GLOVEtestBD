<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/{name}/{lastname}/{email}/{password}/{carne}/{major_id}', [LoginController::class, 'show']);
Route::get('/addMajor/{major_name}', [LoginController::class, 'show']);
