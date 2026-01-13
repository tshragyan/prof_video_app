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
use App\Models\Video;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
//function igShortcodeToId($shortcode) {
//    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
//    $id = 0;
//    foreach (str_split($shortcode) as $c) {
//        $id = $id * 64 + strpos($alphabet, $c);
//    }
//    return $id;
//}
//
$mediaId = igShortcodeToId('DSxbaKDiePK');
dd($mediaId);
//$response = Http::withHeaders([
//    'User-Agent' => 'Instagram 302.1.0.34.111 Android',
//    'X-IG-App-ID' => '936619743392459',
//    'X-Requested-With' => 'XMLHttpRequest',
//    'Referer' => 'https://www.instagram.com/',
//    'Accept' => '*/*'
//])->withCookies([
//    'sessionid' => '78018370422%3AdqW7IuumjKIMh2%3A7%3AAYgHao3UtSslU0-XvmzXGvXefhCtM1WR-lRVw2YNpw',
//    'csrftoken' => 'mxhkkD0Qk0ERRa5Dg2h4WxFaPBgsQ3D9',
//    'ds_user_id' => 78018370422
//], '.instagram.com')
//    ->get("https://www.instagram.com/api/v1/media/{$mediaId}/info/");
//
//$data = $response->json();
//$videoUrl = $data['items'][0]['video_versions'][0]['url'];
//
//$path = storage_path("app/public/instagram/reels");
//if (!File::exists($path)) {
//    File::makeDirectory($path, 0755, true);
//}
//file_put_contents(
//    "$path/{$mediaId}.mp4",
//    file_get_contents($videoUrl)
//);
//dd($data);
//$videoUrl = $data['graphql']['shortcode_media']['video_url'];
//
//file_put_contents(
//    storage_path('reel.mp4'),
//    file_get_contents($videoUrl)
//);
Route::middleware('shopify.host')->group(function() {
    Route::get('/', [DashboardController::class, 'home'])->name('dashboard.home');
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

