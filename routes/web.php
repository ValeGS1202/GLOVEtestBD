<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/newUser/{name}/{lastname}/{email}/{password}/{carne}/{major_id}', [LoginController::class, 'show']);
Route::get('/addMajor/{major_name}', [LoginController::class, 'show']);

Route::get('/register', [RegisterController::class, 'create'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', function () {
    return view('login');
})->name('login.form');

Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/login-success', function () {
    return view('login-success');
})->name('login.success');

//Route::post('/login', [LoginController::class, 'login'])->name('login.check');
