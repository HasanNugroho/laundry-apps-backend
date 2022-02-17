<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\Service\WaktuController;
use App\Http\Controllers\API\Service\OutletController;
use App\Http\Controllers\API\DashboardController;
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
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');
Route::get('/tracking', [PesananController::class, 'tracking'])->name('tracking');

// Route::group(['middleware' => ['signed']], function () {
    Route::put('/forgetpass', [AuthController::class, 'updatePassword'])->name('forgetpass')->middleware('signed');
// });

Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Dashboard
Route::group(['middleware' => ['auth:sanctum', 'cors', 'owner']], function () {
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'countpelangganOwner']);
    Route::get('/dashboard/utang', [DashboardController::class, 'nominalutangOwner']);//v
    Route::get('/dashboard/omset', [DashboardController::class, 'pendapatanOwner']);//v
    Route::get('/dashboard/pengeluaran', [DashboardController::class, 'pengeluaranOwner']);//v
    Route::get('/dashboard/transaksi', [DashboardController::class, 'transaksiOwner']);//v
    Route::get('/dashboard/jumlahtransaksi', [DashboardController::class, 'countTransaksiOwner']);
    Route::get('/dashboard/karyawan', [DashboardController::class, 'daftarKasirOwner']);//v
    Route::get('/adriwayat', [PesananController::class, 'riwayatAdmin']);
    Route::get('/dashboard/adsearch', [DashboardController::class, 'searchAdmin']);//v
    Route::get('/dashboard/operasional', [DashboardController::class, 'operasionalOwner']);//v
    Route::get('/dashboard/counttransaksi', [DashboardController::class, 'countTransaksiAdmin']);//v
    Route::get('/dashboard/pesanan', [DashboardController::class, 'getPesananAdmin']);//v
    Route::get('/dashboard/report', [DashboardController::class, 'report']);//v
    Route::get('/dashboard/reportoperasional', [DashboardController::class, 'reportOperasional']);
    Route::get('/dashboard/reporttransaksi', [DashboardController::class, 'reportTransaksi']);
    Route::get('/dashboard/totalpendapatan', [DashboardController::class, 'totalPemasukanAdmin']);//v
    Route::get('/dashboard/pemasukan', [DashboardController::class, 'pemasukanOwner']);
});

Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::get('/dashboard/kroperasional', [DashboardController::class, 'operasionalKaryawan']);
    Route::get('/dashboard/krutang', [DashboardController::class, 'nominalutangKasir']);
    Route::get('/dashboard/kromset', [DashboardController::class, 'pendapatanKasir']);
    Route::get('/dashboard/krpemasukan', [DashboardController::class, 'pemasukanKasir']);
    Route::get('/dashboard/krtransaksi', [DashboardController::class, 'transaksiKasir']);
    Route::get('/dashboard/krcounttransaksi', [DashboardController::class, 'countTransaksiKasir']);
    Route::get('/dashboard/krpengeluaran', [DashboardController::class, 'pengeluaranKasir']);
    Route::get('/dashboard/krsearch', [DashboardController::class, 'searchKasir']);
    Route::get('/dashboard/krtotalpendapatan', [DashboardController::class, 'totalPemasukanKasir']);
    Route::get('/riwayat', [PesananController::class, 'riwayat']);
});

//pelanggan
Route::group(['middleware' => ['auth:sanctum', 'cors','owner']], function () {
    Route::get('/adpelanggan', [PelangganController::class, 'showadmin']);
});

//pelanggan
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::post('/import-user-client', [AssetController::class, 'importPelanggan']);
    Route::post('/pelanggan', [PelangganController::class, 'create']);
    Route::get('/pelanggan', [PelangganController::class, 'show']);
    Route::get('/pelanggan/{id}', [PelangganController::class, 'showbyid']);
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'delete']);
});

//operasional
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::post('/pengeluaran', [DashboardController::class, 'pengeluaran']);
    // Route::post('/pemasukan', [DashboardController::class, 'pemasukan']);
    Route::get('/operasional', [PesananController::class, 'operasional']);
});

// pesanan
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::post('/pesanan', [PesananController::class, 'create']);
    Route::get('/pesanan/{status}', [PesananController::class, 'getPesanan']);
    Route::get('/pesanan/detail/{nota}', [PesananController::class, 'getPesanandetail']);
    Route::put('/pesanan/status/{id}', [PesananController::class, 'updatestatuspesanan']);
    Route::put('/pesanan/status/pembayaran/{id}', [PesananController::class, 'updatestatuspembayaran']);
});

//outlet
Route::group(['middleware' => ['auth:sanctum', 'cors', 'owner']], function () {
    Route::get('/outlet', [OutletController::class, 'show']);
    Route::get('/outlet/{id}', [OutletController::class, 'showbyid']);
    Route::post('/outlet', [OutletController::class, 'create']);
    Route::put('/outlet/{id}', [OutletController::class, 'update']);
    Route::delete('/outlet/{id}', [OutletController::class, 'delete']);
    Route::post('/outlet/cabang', [OutletController::class, 'tambahCabang']);
    Route::post('/outlet/invite', [OutletController::class, 'invite']);
});

Route::group(['middleware' => ['auth:sanctum', 'cors', 'owner']], function () {
    Route::get('/users', [UserController::class, 'show']);
    Route::get('/users/{id}', [UserController::class, 'showdetil']);
    Route::put('/users/{id}', [UserController::class, 'updaterole']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);
});

// Waktu
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::get('/waktu', [WaktuController::class, 'show']);
    Route::get('/waktu/{id}', [WaktuController::class, 'showById']);
});

//admin
Route::group(['middleware' => ['auth:sanctum', 'cors', 'admin']], function () {
    Route::get('/adwaktu', [WaktuController::class, 'showadmin']);
    Route::post('/waktu', [WaktuController::class, 'create']);
    Route::put('/waktu/{id}', [WaktuController::class, 'update']);
    Route::delete('/waktu/{id}', [WaktuController::class, 'delete']);
});

//service
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
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

Route::group(['middleware' => ['auth:sanctum', 'cors', 'owner']], function () {
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
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::get('/status/pesanan', [AssetController::class, 'status_pesanan']);
    Route::get('/status/pembayaran', [AssetController::class, 'status_pembayaran']);
});

Route::fallback(function () {
    return Response::json("NOT FOUND", 404);
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
