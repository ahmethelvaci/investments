<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // return view('dashboard');
        return redirect('/investors');
    })->name('dashboard');

    Route::resource('assets', AssetController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('investors', InvestorController::class);
    Route::resource('prices', PriceController::class);
});

require __DIR__.'/auth.php';
