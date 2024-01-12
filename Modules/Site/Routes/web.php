<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Site\Http\Controllers\HomeController;
use Modules\Site\Http\Controllers\SiteController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham/{slug}', [SiteController::class, 'detailProduct'])->name('detailProduct');
Route::get('/danh-muc/{slug}', [SiteController::class, 'categoryPage'])->name('categoryPage');
Route::get('/search', [SiteController::class, 'searchPage'])->name('searchPage');





