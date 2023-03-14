<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndicadorController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('indicadors', [IndicadorController::class, 'index'])->name('indicadors.index');
Route::post('indicadors/store', [IndicadorController::class, 'store'])->name('indicadors.store');
Route::get('indicadors/edit/{id}/', [IndicadorController::class, 'edit']);
Route::post('indicadors/update', [IndicadorController::class, 'update'])->name('indicadors.update');
Route::post('/destroy/{id}/', [IndicadorController::class, 'destroy']);

Route::get('/', [IndicadorController::class, 'indexGraf'])->name('/.indexGraf');
Route::post('/',[IndicadorController::class, 'accion']);
