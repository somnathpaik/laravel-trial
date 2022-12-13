<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::prefix('client')->name('client.')->middleware(['auth'])->group(function(){
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/store', [ClientController::class, 'store'])->name('store');
    Route::get('/edit/{uuid}', [ClientController::class, 'edit'])->name('edit');
    Route::post('/update/{uuid}', [ClientController::class, 'update'])->name('update');
    Route::delete('/destroy/{uuid}', [ClientController::class, 'destroy'])->name('destroy');
    Route::get('/search', [ClientController::class, 'search'])->name('search');
    Route::get('/search-data', [ClientController::class, 'searchData'])->name('search-data');
});

require __DIR__.'/auth.php';
