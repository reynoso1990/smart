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
use Modules\Sire\Http\Controllers\SireController;

Route::prefix('sire')->group(function() {
    Route::get('/', [SireController::class,'index']);
    Route::get('/appendix ', [SireController::class,'appendix']);
    Route::get('/generate ', [SireController::class,'generate']);

});
