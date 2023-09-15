<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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



Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{id}/edit', 'UserController@edit')->name('users.edit');
    Route::put('users/{id}', 'UserController@update')->name('users.update');
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/trashed', 'UsersController@trashed')->name('users.trashed');
    Route::delete('users/{id}/delete', 'UserController@delete')->name('users.delete');
    Route::softDeletes('users', 'UserController');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('users/{user}', 'UserController@show')->name('users.show');
// Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Route::get('users/{id}/edit', 'UserController@edit')->name('users.edit');
// Route::put('users/{id}', 'UserController@update')->name('users.update');
// Route::get('/users', 'UserController@index')->name('users.index');
// Route::get('/users/trashed', 'UsersController@trashed')->name('users.trashed');
// Route::delete('users/{id}/delete', 'UserController@delete')->name('users.delete');



