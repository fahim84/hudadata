<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/readdir', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('/emptydb', [\App\Http\Controllers\UserController::class, 'empty_database']);
Route::resource('photos', \App\Http\Controllers\PhotoController::class);
