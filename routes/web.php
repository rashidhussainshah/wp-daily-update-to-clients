<?php

use App\Http\Controllers\Voyager\EodController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
//Route::redirect('/', '/admin/login');
Route::get('/slack-test', function () {
    Log::channel('slackEODNotificationLog')->info('New log is created');

//    Log::critical('This is a critical message Sent from Laravel App');
    return view('welcome');
});
Route::permanentRedirect('/', 'admin/login');

//Route::get('/', function () {
//    App::setLocale('pt');
//    return view('welcome');
//});

Route::group(['prefix' => 'admin'], function () {
    Route::get('eod-content', [EodController::class, 'eodContent'])->name('eod.get');
    Voyager::routes();
});
