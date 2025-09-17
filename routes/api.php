<?php

use App\Http\Controllers\ClockifyController;
use App\Http\Controllers\DeveloperCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientPortfolioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::middleware('verify.api.source')->group(function () {
    Route::get('developer-categories', [DeveloperCategoryController::class, 'index']);
    Route::get('developer-categories/{id}/developers', [DeveloperCategoryController::class, 'show']);
    Route::get('developer-portfolios/{id}', [DeveloperCategoryController::class, 'developerPortfolios']);
//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('client-portfolios/{id}', [ClientPortfolioController::class, 'show']);
