<?php

use App\Http\Controllers\TaskFirstController;
use App\Http\Controllers\TaskTicketBarcodeController;
use App\Http\Controllers\TaskTwoController;
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

Route::get('/one', [TaskFirstController::class, 'index']);
Route::get('/two', [TaskTwoController::class, 'index']);
Route::get('/ticket', [TaskTicketBarcodeController::class, 'index']);

