<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\Service\WaktuController;
use App\Http\Controllers\API\Service\OutletController;
// use App\Http\Controllers\API\Service\KiloanController;
// use App\Http\Controllers\API\Service\SatuanController;
use App\Http\Controllers\API\Service\ServiceController;
use App\Http\Controllers\API\Service\PesananController;
use App\Http\Controllers\API\Service\PelangganController;

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
Route::post('/registerKr', [AuthController::class, 'registerKaryawan'])->name('registerKaryawan');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

//pelanggan
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/pelanggan', [PelangganController::class, 'create']);
    Route::get('/pelanggan', [PelangganController::class, 'show']);
    Route::get('/pelanggan/{id}', [PelangganController::class, 'showbyid']);
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'delete']);
});

// pesanan
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/pesanan', [PesananController::class, 'create']);
    Route::get('/pesanan/outlet/{outletid}/{status}', [PesananController::class, 'getPesanan']);
    Route::get('/pesanan/{nota}', [PesananController::class, 'getPesanandetail']);
    Route::put('/pesanan/status/{id}', [PesananController::class, 'updatestatus']);
});

//outlet
Route::group(['middleware' => ['auth:sanctum', 'owner']], function () {
    Route::get('/outlet', [OutletController::class, 'show']);
    Route::get('/outlet/{id}', [OutletController::class, 'showbyid']);
    Route::post('/outlet', [OutletController::class, 'create']);
    Route::post('/outlet/cabang', [OutletController::class, 'tambahCabang']);
    Route::post('/outlet/invite', [OutletController::class, 'invite']);
});

Route::group(['middleware' => ['auth:sanctum', 'owner']], function () {
    Route::get('/users', [UserController::class, 'showall']);
    Route::get('/users/{id}', [UserController::class, 'showdetil']);
    Route::put('/users/{id}', [UserController::class, 'updaterole']);
});

// Waktu
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/waktu', [WaktuController::class, 'show']);
    Route::get('/waktu/{id}', [WaktuController::class, 'showById']);
});

//admin
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::get('/adwaktu', [WaktuController::class, 'showadmin']);
    Route::post('/waktu', [WaktuController::class, 'create']);
    Route::put('/waktu/{id}', [WaktuController::class, 'update']);
    Route::delete('/waktu/{id}', [WaktuController::class, 'delete']);
});

//service
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/service', [ServiceController::class, 'show']);
    Route::get('/service/{id}', [ServiceController::class, 'showById']);
});

//admin
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::get('/adservice', [ServiceController::class, 'showadmin']);
    Route::post('/service', [ServiceController::class, 'create']);
    Route::put('/service/{id}', [ServiceController::class, 'update']);
    Route::delete('/service/{id}', [ServiceController::class, 'delete']);
});

Route::group(['middleware' => ['auth:sanctum', 'owner']], function () {
    Route::post('/provinsi', [AssetController::class, 'provinsi']);
    Route::post('/kabupaten', [AssetController::class, 'kabupaten']);
    Route::post('/kecamatan', [AssetController::class, 'kecamatan']);
    Route::post('/kelurahan', [AssetController::class, 'kelurahan']);
    Route::get('/kelurahan/{id}', [AssetController::class, 'get_kelurahan']);
    Route::get('/kabupaten_kota/{id}', [AssetController::class, 'get_kabupaten']);
    Route::get('/kecamatan/{id}', [AssetController::class, 'get_kecamatan']);
    Route::get('/provinsi', [AssetController::class, 'get_provinsi']);
});

//status
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/status/pesanan', [AssetController::class, 'status_pesanan']);
    Route::get('/status/pembayaran', [AssetController::class, 'status_pembayaran']);
});

//riwayat
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/riwayat', [PesananController::class, 'riwayat']);
    // Route::get('/riwayatall', [PesananController::class, 'riwayatall']);
});


Route::fallback(function () {
    return Response::json("FORBIDDEN", 403);
});

// //Kiloan
// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/service/kiloan', [KiloanController::class, 'show']);
//     Route::get('/service/kiloan/{id}', [KiloanController::class, 'showById']);
// });

// Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
//     Route::post('/service/kiloan', [KiloanController::class, 'create']);
//     Route::put('/service/kiloan/{id}', [KiloanController::class, 'update']);
//     Route::delete('/service/kiloan/{id}', [KiloanController::class, 'delete']);
// });

// //satuan
// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/service/satuan', [SatuanController::class, 'show']);
//     Route::get('/service/satuan/{id}', [SatuanController::class, 'showById']);
// });

// Route::group(['middleware' => ['auth:sanctum'], 'owner'], function () {
//     Route::post('/service/satuan', [SatuanController::class, 'create']);
//     Route::put('/service/satuan/{id}', [SatuanController::class, 'update']);
//     Route::delete('/service/satuan/{id}', [SatuanController::class, 'delete']);
// });
