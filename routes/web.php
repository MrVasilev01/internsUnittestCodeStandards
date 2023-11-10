<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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

Auth::routes();



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

Auth::routes();

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth')->group(function () {

        // Dashboard
        Route::get('/home', [HomeController::class, 'dashboard'])->name('dashboard');

        // User-related routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/management', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/create', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('/edit/{id}', [UserController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Car-related routes
        Route::prefix('car')->name('car.')->group(function () {
            Route::get('/management', [CarController::class, 'index'])->name('index');
            Route::get('/create', [CarController::class, 'create'])->name('create');
            Route::post('/create', [CarController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CarController::class, 'edit'])->name('edit');
            Route::post('/edit/{id}', [CarController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [CarController::class, 'destroy'])->name('destroy');
        });
    });
});