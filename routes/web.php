<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ClockifyController;
use App\Http\Controllers\SalaryInvoiceController;
use App\Http\Controllers\Voyager\DeveloperPaymentController;
use App\Http\Controllers\Voyager\EmailCampaignController;
use App\Http\Controllers\Voyager\LeaveController;
use App\Http\Controllers\Voyager\EodController;
use Illuminate\Support\Facades\Route;
use Spatie\SlackAlerts\Facades\SlackAlert;
use TCG\Voyager\Facades\Voyager;


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
    Route::get('clockify/today-entries', [ClockifyController::class, 'getTodayEntries'])->name('clockify.today-entries');
    Route::get('/get-yesterdays-plan', [CheckinController::class, 'getYesterdaysPlan'])->name('get.yesterdays.plan');
    Route::post('/checkin', [CheckinController::class, 'storeCheckin'])->name('checkin.store');
    Route::post('/checkout', [CheckinController::class, 'storeCheckout'])->name('checkout.store');
    Route::get('eod-content', [EodController::class, 'eodContent'])->name('eod.get');
    Route::get('mark-user-payment-paid/{id}', [DeveloperPaymentController::class, 'markUserPaymentPaid'])->name('mark-user-payment-paid');
    Route::get('leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');

    // Salary Invoice routes
    Route::get('salary-invoice/pdf', [SalaryInvoiceController::class, 'viewPdf'])->name('salary-invoice.pdf');
    Route::get('salary-invoice/html', [SalaryInvoiceController::class, 'viewHtml'])->name('salary-invoice.html');

    // Email Campaigns
    Route::prefix('email-campaigns')->name('email-campaigns.')->group(function () {
        Route::get('/',                        [EmailCampaignController::class, 'index'])->name('index');
        Route::get('/create',                  [EmailCampaignController::class, 'create'])->name('create');
        Route::post('/',                       [EmailCampaignController::class, 'store'])->name('store');
        Route::get('/{id}',                    [EmailCampaignController::class, 'show'])->name('show');
        Route::get('/{id}/edit',               [EmailCampaignController::class, 'edit'])->name('edit');
        Route::put('/{id}',                    [EmailCampaignController::class, 'update'])->name('update');
        Route::delete('/{id}',                 [EmailCampaignController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/preview',            [EmailCampaignController::class, 'preview'])->name('preview');
        Route::post('/{id}/send-test',         [EmailCampaignController::class, 'sendTest'])->name('send-test');
        Route::post('/{id}/send-single',       [EmailCampaignController::class, 'sendSingle'])->name('send-single');
        Route::post('/{id}/dispatch',          [EmailCampaignController::class, 'dispatch'])->name('dispatch');
        Route::post('/{id}/mark-complete',     [EmailCampaignController::class, 'markComplete'])->name('mark-complete');
        Route::get('/{id}/recipients',         [EmailCampaignController::class, 'searchRecipients'])->name('recipients');
        Route::post('/{id}/send-to-selected',  [EmailCampaignController::class, 'sendToSelected'])->name('send-to-selected');
    });

    Voyager::routes();
});
