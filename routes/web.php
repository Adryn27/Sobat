<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\SawController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect()->route('backend.login');
});

//Login
Route::get('/login',[LoginController::class,'loginBackend'])->name('backend.login');
Route::post('/login',[LoginController::class,'authenticateBackend'])->name('backend.login');
Route::post('/logout',[LoginController::class,'logoutBackend'])->name('backend.logout');

//Verifikasi Email
Route::get('/verify-email/{token}',[LoginController::class,'verifyEmail'])->name('verify.email');
Route::post('/user/{id}/verification-update', [UserController::class, 'verifikasiUlang'])->name('backend.verifulang')->middleware('auth');

//Set Password
Route::post('/set-password/{token}',[LoginController::class,'setPassword'])->name('set.password');

//Dashboard
Route::get('/dashboard',[BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth');
Route::get('/dashboard/data',[BerandaController::class, 'getData'])->name('backend.data')->middleware('auth');


//User
Route::resource('/user',UserController::class, ['as'=>'backend'])->middleware(['auth', 'role:0,2']);
Route::get('/profile', [UserController::class, 'profile'])->name('backend.profile')->middleware('auth');
Route::post('/user/{id}/reset', [UserController::class, 'linkReset'])->name('backend.reset')->middleware(['auth', 'role:0,2']);
Route::get('/reset-password/{token}', [LoginController::class, 'formReset'])->name('password.reset');
Route::post('/password-update', [LoginController::class, 'prosesReset'])->name('password.update');

//Management Data
Route::resource('/supplier',PemasokController::class, ['as'=>'backend'])->middleware('auth');
Route::resource('/category',KategoriController::class, ['as'=>'backend'])->middleware('auth');
Route::post('/product/import', [ObatController::class, 'import'])->name('backend.product.import')->middleware('auth');
Route::get('/product/export', [ObatController::class, 'export'])->name('backend.product.export')->middleware('auth');
Route::resource('/product',ObatController::class, ['as'=>'backend'])->middleware('auth');

//Sales
Route::resource('/cashier',KasirController::class, ['as'=>'backend'])->middleware('auth');
Route::get('/history', [KasirController::class, 'history'])->name('backend.cashier.history')->middleware('auth');
Route::post('/history/import', [KasirController::class, 'import'])->name('backend.cashier.import')->middleware('auth');
Route::get('/history/export', [KasirController::class, 'export'])->name('backend.cashier.export')->middleware('auth');

//Management Stock
Route::resource('/stock',MutasiController::class, ['as'=>'backend'])->middleware('auth');
Route::get('/inventory', [MutasiController::class, 'inven'])->name('backend.stock.inven')->middleware('auth');
Route::get('/inventory/pdf', [MutasiController::class, 'print'])->name('backend.stock.pdf');

//Analytics
Route::resource('/analysis', SawController::class, ['as'=>'backend'])->middleware('auth');
Route::post('/analysis/process', [SawController::class, 'proses'])->name('backend.analysis.process')->middleware('auth');

//Report
Route::get('/report/sales', [KasirController::class, 'report'])->name('backend.cashier.report')->middleware('auth');
Route::get('/report/sales/pdf', [KasirController::class, 'exportPdf'])->name('backend.cashier.pdf');
