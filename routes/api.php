<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Schedule_CourseController;
use App\Http\Controllers\Calendar_EventController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cursos', [Schedule_CourseController::class, 'all']);

Route::post('/cursos', [Schedule_CourseController::class, 'store']);

Route::get('/eventos', [Calendar_EventController::class, 'all']);
Route::post('/eventos', [Calendar_EventController::class, 'store']);
Route::delete('/eventos/{id}', [Calendar_EventController::class, 'destroy']);
