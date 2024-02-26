<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
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


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('ck/{gedung}/{lobi}', [TamuController::class, 'formCheckout'])->name('checkout');


Route::get('keluar', [AuthController::class, 'keluar'])->name('keluar');
Route::post('login', [AuthController::class, 'postLogin'])->name('post.login');

Route::get('captcha-reload', [AuthController::class, 'reloadCaptcha']);
Route::get('test/form/{gedung}/{lobi}', [TamuController::class, 'testCreate'])->name('tamu.create.test');
Route::get('form/{gedung}/{lobi}', [TamuController::class, 'create'])->name('tamu.create');
Route::get('form/berhasil/{gedung}/{lobi}/{id}', [TamuController::class, 'confirm'])->name('tamu.confirm');

Route::post('form/berhasil/{gedung}/{lobi}/{id}', [TamuController::class, 'confirm'])->name('tamu.no_visitor');
Route::post('form/{gedung}', [TamuController::class, 'store'])->name('tamu.store');

Route::get('survei-kepuasan', [TamuController::class, 'survei'])->name('survei');
Route::get('checkout/tamu/{survei}/{id}', [TamuController::class, 'checkoutStore'])->name('checkout.store');

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/waktu', [DashboardController::class, 'time'])->name('dashboard.time');

    Route::get('user', [UserController::class, 'index'])->name('user.show');
    Route::get('user/detail/{id}', [UserController::class, 'detail'])->name('user.detail');
    Route::get('user/tambah', [UserController::class, 'create'])->name('user.create');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('user/tambah', [UserController::class, 'store'])->name('user.store');

    Route::get('tamu', [TamuController::class, 'show'])->name('tamu.show');
    Route::get('tamu/tambah', [TamuController::class, 'createByAdmin'])->name('tamu.admin.create');
    Route::get('tamu/data/grafik/{id}/{bulan}/{tahun}', [TamuController::class, 'grafik'])->name('tamu.chart');
    Route::get('tamu/edit/{id}', [TamuController::class, 'edit'])->name('tamu.edit');
    Route::get('tamu/daftar/hapus/{id}', [TamuController::class, 'destroy'])->name('tamu.delete');
    Route::get('tamu/daftar/filter/show', [TamuController::class, 'filter'])->name('tamu.filter');
    Route::get('tamu/daftar/download/cetak', [TamuController::class, 'filter'])->name('tamu.download');
    Route::get('tamu/keluar/{id}', [TamuController::class, 'leave'])->name('tamu.leave');
    Route::post('tamu/edit/{id}', [TamuController::class, 'update'])->name('tamu.update');
    Route::post('tamu/tambah', [TamuController::class, 'storeByAdmin'])->name('tamu.admin.store');

    Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.show');
    Route::get('pegawai/tambah', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::get('pegawai/hapus/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.delete');
    Route::post('pegawai/tambah', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::post('pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

    Route::get('gedung/select', [GedungController::class, 'select'])->name('gedung.select');
    Route::get('area/select/{id}', [AreaController::class, 'select'])->name('area.select');
    Route::get('survey/chart', [TamuController::class, 'surveyGrafik'])->name('survey.chart');
});
