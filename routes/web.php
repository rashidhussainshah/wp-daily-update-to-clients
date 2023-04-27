<?php

use App\Http\Controllers\Voyager\EodController;
use Illuminate\Support\Facades\Route;
use Spatie\SlackAlerts\Facades\SlackAlert;


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
    try {
        \Artisan::call('optimize');
        $text = '<p style="padding-left: 40px;">Hi&nbsp;<strong>Web Penter,<br /></strong>Today work report</p>
<p>&nbsp;</p>
<ul>
<li>integrate slack into portal</li>
<li>share daily updates over slack</li>
<li>multiple channel integration</li>
</ul>
<p style="padding-left: 40px;">RASHID BUKHARI<br />Senior Software Engineer<br />+92 300 8968490<br /><a title="Webpenter " href="webpenter.com" target="_blank" rel="noopener">webpenter.com</a></p>';
        $taglessBody = strip_tags($text);
        SlackAlert::to('https://hooks.slack.com/services/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')->message($taglessBody);

//        SlackAlert::message($taglessBody);

//        SlackAlert::blocks([
//            [
//                "type" => "section",
//                "text" => [
//                    "type" => "mrkdwn",
//                    "text" => $taglessBody
//                ]
//            ]
//        ]);
//        SlackAlert::message("You have a new subscriber to the rashid newsletter!");

    } catch (Exception $exception) {
        dd($exception);
    }
//    SlackAlert::message("You have a new subscriber to the newsletter!");
    // Log::channel('slackEODNotificationLog')->info('New log is created');

//    Log::critical('This is a critical message Sent from Laravel App');
//    return view('welcome');
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
