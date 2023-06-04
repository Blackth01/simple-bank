<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//People routes
use App\Http\Controllers\PersonController;

Route::get('person', [PersonController::class, 'index']);
Route::get('person/{id}', [PersonController::class, 'show']);
Route::get('person/accounts/{id}', [PersonController::class, 'get_accounts']);
Route::post('person', [PersonController::class, 'store']);
Route::put('person/{id}', [PersonController::class, 'update']);
Route::delete('person/{id}', [PersonController::class,'destroy']);

//Account routes
use App\Http\Controllers\AccountController;

Route::get('account', [AccountController::class, 'index']);
Route::get('account/{id}', [AccountController::class, 'show']);
Route::get('account/statement/{id}', [AccountController::class, 'get_statement']);
Route::post('account', [AccountController::class, 'store']);
Route::put('account/{id}', [AccountController::class, 'update']);
Route::delete('account/{id}', [AccountController::class,'destroy']);

//AccountStatement routes
use App\Http\Controllers\AccountStatementController;

Route::post('transaction', [AccountStatementController::class, 'store']);