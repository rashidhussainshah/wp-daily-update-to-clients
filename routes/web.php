<?php

use App\Http\Controllers\Voyager\EodController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleLoginController;

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
Route::get('/mail/{email_name}/{to_email?}/{send_email?}', [\App\Http\Controllers\TestController::class, 'testEmail']);
Route::permanentRedirect('/', 'admin/login');

//Route::get('/', function () {
//    App::setLocale('pt');
//    return view('welcome');
//});

Route::group(['prefix' => 'admin'], function () {
    Route::get('eod-content', [EodController::class, 'eodContent'])->name('eod.get');
    Voyager::routes();
});
Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');

// Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleCallback'])->name('google.callback');
