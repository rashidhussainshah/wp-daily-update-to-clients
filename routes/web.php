<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ClockifyController;
use App\Http\Controllers\SalaryInvoiceController;
use App\Http\Controllers\Voyager\DeveloperPaymentController;
use App\Http\Controllers\Voyager\EmailCampaignController;
use App\Http\Controllers\Voyager\CampaignAutomationController;
use App\Http\Controllers\Voyager\SmtpAccountController;
use App\Http\Controllers\Voyager\EmailSignatureController;
use App\Http\Controllers\Voyager\FinancialsController;
use App\Http\Controllers\Voyager\LeaveController;
use App\Http\Controllers\Voyager\MyDevicesController;
use App\Http\Controllers\Voyager\CompanyDeviceMaintenanceLogController;
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
    Route::post('user-payments/{id}/attach-invoice', [DeveloperPaymentController::class, 'attachInvoice'])->name('user-payments.attach-invoice');
    Route::post('user-payments/{id}/rate-approve', [DeveloperPaymentController::class, 'rateApprove'])->name('user-payments.rate-approve');
    Route::post('user-payments/quick-add-project', [DeveloperPaymentController::class, 'quickAddProject'])->name('user-payments.quick-add-project');
    Route::post('user-payments/quick-add-target', [DeveloperPaymentController::class, 'quickAddTarget'])->name('user-payments.quick-add-target');
    Route::get('user-payments-statistics', [DeveloperPaymentController::class, 'statistics'])->name('user-payments.statistics');
    Route::get('team-statistics', [\App\Http\Controllers\Voyager\TeamStatisticsController::class, 'index'])->name('team-statistics.index');
    Route::get('payment-flow', [DeveloperPaymentController::class, 'flowGuide'])->name('user-payments.flow-guide');
    Route::get('leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');

    // My Devices - read-only view of company devices assigned to the logged-in user
    Route::get('my-devices', [MyDevicesController::class, 'index'])->name('my-devices.index');

    // Company Device Maintenance Logs - quick "log a repair" (battery, hard drive, etc.)
    // from the device detail page, see resources/views/vendor/voyager/company-devices/read.blade.php
    Route::post('company-devices/{device}/maintenance-logs', [CompanyDeviceMaintenanceLogController::class, 'store'])
        ->name('company-device-maintenance-logs.store');
    Route::delete('company-device-maintenance-logs/{log}', [CompanyDeviceMaintenanceLogController::class, 'destroy'])
        ->name('company-device-maintenance-logs.destroy');

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

    // SMTP Accounts
    Route::prefix('smtp-accounts')->name('smtp-accounts.')->group(function () {
        Route::get('/',              [SmtpAccountController::class, 'index'])->name('index');
        Route::get('/create',        [SmtpAccountController::class, 'create'])->name('create');
        Route::post('/',             [SmtpAccountController::class, 'store'])->name('store');
        Route::get('/{id}/edit',     [SmtpAccountController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [SmtpAccountController::class, 'update'])->name('update');
        Route::delete('/{id}',       [SmtpAccountController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/send-test', [SmtpAccountController::class, 'sendTest'])->name('send-test');
    });

    // Campaign Automations
    Route::prefix('campaign-automations')->name('campaign-automations.')->group(function () {
        Route::get('/',          [CampaignAutomationController::class, 'index'])->name('index');
        Route::get('/guide',     [CampaignAutomationController::class, 'guide'])->name('guide');
        Route::get('/setup-guide', [CampaignAutomationController::class, 'setupGuide'])->name('setup-guide');
        Route::post('/process-queue', [CampaignAutomationController::class, 'processQueue'])->name('process-queue');
        Route::get('/create',    [CampaignAutomationController::class, 'create'])->name('create');
        Route::post('/',         [CampaignAutomationController::class, 'store'])->name('store');
        Route::get('/{id}',      [CampaignAutomationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CampaignAutomationController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [CampaignAutomationController::class, 'update'])->name('update');
        Route::delete('/{id}',   [CampaignAutomationController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/pause',   [CampaignAutomationController::class, 'pause'])->name('pause');
        Route::post('/{id}/resume',  [CampaignAutomationController::class, 'resume'])->name('resume');
        Route::post('/{id}/cancel',  [CampaignAutomationController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/run-now', [CampaignAutomationController::class, 'runNow'])->name('run-now');
    });

    // Email Signatures
    Route::prefix('email-signatures')->name('email-signatures.')->group(function () {
        Route::get('/',          [EmailSignatureController::class, 'index'])->name('index');
        Route::get('/create',    [EmailSignatureController::class, 'create'])->name('create');
        Route::post('/',         [EmailSignatureController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EmailSignatureController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [EmailSignatureController::class, 'update'])->name('update');
        Route::delete('/{id}',   [EmailSignatureController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/preview', [EmailSignatureController::class, 'preview'])->name('preview');
    });

    // Financials / P&L
    Route::prefix('financials')->name('financials.')->group(function () {
        Route::get('/',                              [FinancialsController::class, 'index'])->name('index');
        Route::get('/quick-expense',                 [FinancialsController::class, 'quickExpense'])->name('quick-expense');
        Route::get('/charts',                        [FinancialsController::class, 'charts'])->name('charts');
        Route::post('/bank-balances',                [FinancialsController::class, 'storeBankBalance'])->name('store-bank-balance');
        Route::get('/cash',                          [FinancialsController::class, 'cash'])->name('cash');
        Route::post('/cash',                         [FinancialsController::class, 'storeCash'])->name('store-cash');
        Route::delete('/cash/{id}',                  [FinancialsController::class, 'destroyCash'])->name('destroy-cash');
        Route::post('/expenses',                     [FinancialsController::class, 'storeExpense'])->name('store-expense');
        Route::get('/expenses/{id}',                 [FinancialsController::class, 'showExpense'])->name('show-expense');
        Route::delete('/expenses/{id}',              [FinancialsController::class, 'destroyExpense'])->name('destroy-expense');
        Route::post('/expenses/seed-fixed',          [FinancialsController::class, 'seedFixedExpenses'])->name('seed-fixed');
        Route::post('/bd-targets',                   [FinancialsController::class, 'storeBdTarget'])->name('store-bd-target');
        Route::post('/domains',                      [FinancialsController::class, 'storeDomain'])->name('store-domain');
        Route::patch('/domains/{id}/renew',          [FinancialsController::class, 'renewDomain'])->name('renew-domain');
    });

    Voyager::routes();
});
