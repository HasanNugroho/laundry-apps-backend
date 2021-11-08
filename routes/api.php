<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Service\WaktuController;
use App\Http\Controllers\API\Service\OutletController;
use App\Http\Controllers\API\Service\KiloanController;
use App\Http\Controllers\API\Service\SatuanController;
use App\Http\Controllers\API\Service\PesananController;

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
// auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// pesanan
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/pesanan', [PesananController::class, 'create']);
});

//outlet
Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
    Route::post('/outlet', [OutletController::class, 'create']);
});

// Waktu
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/waktu', [WaktuController::class, 'show']);
    Route::get('/waktu/{id}', [WaktuController::class, 'showById']);
});

Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
    Route::post('/waktu', [WaktuController::class, 'create']);
    Route::put('/waktu/{id}', [WaktuController::class, 'update']);
    Route::delete('/waktu/{id}', [WaktuController::class, 'delete']);
});

//Kiloan
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/kiloan', [KiloanController::class, 'show']);
    Route::get('/kiloan/{id}', [KiloanController::class, 'showById']);
});

Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
    Route::post('/kiloan', [KiloanController::class, 'create']);
    Route::put('/kiloan/{id}', [KiloanController::class, 'update']);
    Route::delete('/kiloan/{id}', [KiloanController::class, 'delete']);
});

//satuan
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/satuan', [SatuanController::class, 'show']);
    Route::get('/satuan/{id}', [SatuanController::class, 'showById']);
});

Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
    Route::post('/satuan', [SatuanController::class, 'create']);
    Route::put('/satuan/{id}', [SatuanController::class, 'update']);
    Route::delete('/satuan/{id}', [SatuanController::class, 'delete']);
});

Route::fallback(function () {
    return Response::json(["error" => "not found"], 404);
});