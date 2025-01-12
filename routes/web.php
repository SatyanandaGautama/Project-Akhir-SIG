<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewMapController; 

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
    return view('home');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';

Route::get('/rute', [ViewMapController::class, 'showRute']);
Route::get('/map', [ViewMapController::class, 'showMap']);
Route::get('/add', [ViewMapController::class, 'showAdd']);
Route::post('/api/markers', [ViewMapController::class, 'storeMarker']);
Route::get('/api/markers', [ViewMapController::class, 'getMarkers']);
Route::get('/api/markers/{id}', [ViewMapController::class, 'viewMarker']);
Route::delete('/api/markers/{id}', [ViewMapController::class, 'deleteMarker']);



