<?php

use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShopifyErrorLogController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketMessageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\PlanChargeRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VideoController as ApiVideoController;
use App\Http\Controllers\DashboardController;
use danog\MadelineProto\API;
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



//Route::get('im-hrashq', [DashboardController::class, 'downloader']);
//Route::post('download', [DashboardController::class, 'download'])->name('download.insta');

Route::get('/', [DashboardController::class, 'home'])->name('dashboard.home');
Route::middleware('shopify.host')->group(function() {
    Route::get('/videos', [ApiVideoController::class, 'list'])->name('videos.list');
    Route::prefix('auth')->name('shopify.')->group(function() {
        Route::get('install', [AuthController::class, 'install'])->name('shopify.install');
        Route::get('callback', [AuthController::class, 'callback'])->name('callback');
    });
});

Route::get('/access-denied', function () {
    return view('errors.access_denied');
})->name('access_denied');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('signIn', [AdminController::class, 'signIn'])->name('signIn');
    Route::post('login', [AdminController::class, 'login'])->name('login');
    Route::get('logout', [AdminController::class, 'logout'])->name('logout');
    Route::middleware(['admin.auth'])->group(function() {
        Route::get('/', [AdminController::class, 'index']);

        Route::resource('users', UserController::class);
        Route::resource('videos', VideoController::class);
        Route::resource('products', ProductController::class);
        Route::resource('plans', PlanController::class);

        Route::get('tickets', [TicketController::class, 'new'])->name('tickets.new');
        Route::get('tickets/read', [TicketController::class, 'read'])->name('tickets.read');
        Route::get('tickets/in-progress', [TicketController::class, 'inProgress'])->name('tickets.in-progress');
        Route::get('tickets/resolved', [TicketController::class, 'resolved'])->name('tickets.resolved');
        Route::get('tickets/show/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::put('tickets/update/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

        Route::get('plan-charge-requests', [PlanChargeRequestController::class, 'index'])->name('plan_charge_requests.index');
        Route::get('plan-charge-requests/show/{planChargeRequest}', [PlanChargeRequestController::class, 'show'])->name('plan_charge_requests.show');
        Route::get('shopify-error-logs', [ShopifyErrorLogController::class, 'index'])->name('shopify_error_logs.index');
        Route::get('shopify-error-logs/show/{shopify_error_log}', [ShopifyErrorLogController::class, 'show'])->name('shopify_error_logs.show');

        Route::post('ticket-messages/create/{ticket}', [TicketMessageController::class, 'create'])->name('ticket-messages.create');
    });
});

//die('aaaaaa');

