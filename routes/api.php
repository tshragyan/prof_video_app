<?php

use App\Http\Controllers\Api\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('video')->name('video.')->middleware(['shopify.auth'])->group(function () {
    Route::post('upload', [VideoController::class, 'upload'])->name('upload');
    Route::post('import-from-instagram', [VideoController::class, 'importFromInstagram'])->name('import-from-instagram');
});
