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

// WebPenter IT Academy - public routes (no login required)
Route::prefix('academy')->name('academy.')->group(function () {
    Route::get('register', [\App\Http\Controllers\AcademyRegistrationController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\AcademyRegistrationController::class, 'store'])->name('register.store');
    Route::get('register/success/{enrollment}', [\App\Http\Controllers\AcademyRegistrationController::class, 'success'])->name('register.success');
    Route::get('parent/{token}', [\App\Http\Controllers\AcademyParentController::class, 'show'])->name('parent');
});
Route::get('certificate/verify/{code}', [\App\Http\Controllers\AcademyCertificateController::class, 'verify'])->name('academy.certificate.verify');
Route::get('hr-documents/verify/{code}', [\App\Http\Controllers\DocumentVerifyController::class, 'verify'])->name('hr-documents.verify');
Route::get('academy/badge/{token}', [\App\Http\Controllers\AcademyStudentBadgeController::class, 'show'])->name('academy.student-badge.show');

// WebPenter IT Academy - authenticated routes (student dashboard + instructor
// view). Plain 'auth' middleware - same Users table/session as Voyager admin,
// role-checked inside each controller rather than a separate auth system.
Route::middleware('auth')->prefix('academy')->name('academy.')->group(function () {
    Route::get('help', [\App\Http\Controllers\AcademyHelpController::class, 'index'])->name('help');
    Route::get('dashboard', [\App\Http\Controllers\AcademyDashboardController::class, 'index'])->name('dashboard');
    Route::post('dashboard/toggle-skill', [\App\Http\Controllers\AcademyDashboardController::class, 'toggleSkill'])->name('dashboard.toggle-skill');
    Route::post('dashboard/submit', [\App\Http\Controllers\AcademyDashboardController::class, 'submitProject'])->name('dashboard.submit');
    Route::post('dashboard/fee/{invoice}/submit-proof', [\App\Http\Controllers\AcademyDashboardController::class, 'submitPaymentProof'])->name('dashboard.submit-payment-proof');
    Route::get('instructor', [\App\Http\Controllers\AcademyInstructorController::class, 'index'])->name('instructor');
    Route::get('reviewer', [\App\Http\Controllers\AcademyReviewerDashboardController::class, 'index'])->name('reviewer.index');
    Route::get('marketing', [\App\Http\Controllers\AcademyMarketingDashboardController::class, 'index'])->name('marketing.index');
    Route::post('marketing/certificates/{certificate}/suggest-post', [\App\Http\Controllers\AcademyMarketingDashboardController::class, 'suggestPost'])->name('marketing.suggest-post');
    Route::post('marketing/certificates/{certificate}/mark-posted', [\App\Http\Controllers\AcademyMarketingDashboardController::class, 'markPosted'])->name('marketing.mark-posted');
    Route::get('marketing/certificates/{certificate}/download', [\App\Http\Controllers\AcademyMarketingDashboardController::class, 'downloadPdf'])->name('marketing.download');

    Route::prefix('accountant')->name('accountant.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AcademyAccountantDashboardController::class, 'index'])->name('index');
        Route::post('{invoice}/mark-paid', [\App\Http\Controllers\AcademyAccountantDashboardController::class, 'markPaid'])->name('mark-paid');
        Route::get('students', [\App\Http\Controllers\AcademyAccountantDashboardController::class, 'students'])->name('students');
        Route::delete('students/{enrollment}', [\App\Http\Controllers\AcademyAccountantDashboardController::class, 'removeStudent'])->name('remove-student');
    });
});

Route::group(['prefix' => 'admin'], function () {
    // WebPenter IT Academy - staff screens. This 'admin' group also carries
    // Voyager::routes() itself (below) plus a lot of pre-existing unrelated
    // admin routes - do NOT add middleware at this outer level, it would
    // apply to all of those too (this previously broke Voyager's own login
    // page: 'auth' on this whole group meant a guest hitting admin/login
    // got redirected by 'auth' back to admin/login - an infinite loop).
    // Scope 'auth' to just this academy sub-group instead. Voyager's own
    // 'admin.user' middleware isn't used here because it additionally
    // requires the 'browse_admin' permission, which an Academy reviewer/
    // instructor may not hold - so this uses plain 'auth' (redirect-to-login
    // for guests) and leaves the actual role/capability check to each
    // controller's authorizeStaff(), same as the student-side group above.
    Route::prefix('academy')->name('academy.')->middleware('auth')->group(function () {
        Route::get('review', [\App\Http\Controllers\Voyager\AcademyReviewController::class, 'index'])->name('review.index');
        Route::post('review/{review}/approve', [\App\Http\Controllers\Voyager\AcademyReviewController::class, 'approve'])->name('review.approve');
        Route::post('review/{review}/send-back', [\App\Http\Controllers\Voyager\AcademyReviewController::class, 'sendBack'])->name('review.send-back');
        Route::get('fees', [\App\Http\Controllers\Voyager\AcademyFeeController::class, 'index'])->name('fees.index');
        Route::post('fees/{invoice}/mark-paid', [\App\Http\Controllers\Voyager\AcademyFeeController::class, 'markPaid'])->name('fees.mark-paid');
        Route::get('course-certificates', [\App\Http\Controllers\Voyager\AcademyCourseCertificateController::class, 'create'])->name('course-certificates.create');
        Route::post('course-certificates', [\App\Http\Controllers\Voyager\AcademyCourseCertificateController::class, 'store'])->name('course-certificates.store');
        Route::get('certificates', [\App\Http\Controllers\Voyager\AcademyMarketingCertificatesController::class, 'index'])->name('certificates.index');
        Route::get('student-cards', [\App\Http\Controllers\Voyager\AcademyStudentCardController::class, 'index'])->name('student-cards.index');
        Route::post('student-cards/print', [\App\Http\Controllers\Voyager\AcademyStudentCardController::class, 'print'])->name('student-cards.print');
        Route::get('student-cards/batches/{batch}/print', [\App\Http\Controllers\Voyager\AcademyStudentCardController::class, 'printBatch'])->name('student-cards.print-batch');
        Route::post('student-cards/batches/{batch}/mark-printed', [\App\Http\Controllers\Voyager\AcademyStudentCardController::class, 'markBatchPrinted'])->name('student-cards.mark-batch-printed');
        Route::post('student-cards/batch-items/{item}/mark-printed', [\App\Http\Controllers\Voyager\AcademyStudentCardController::class, 'markItemPrinted'])->name('student-cards.mark-item-printed');

        Route::prefix('card-batches')->name('card-batches.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'store'])->name('store');
            Route::post('add-students', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'addStudents'])->name('add-students');
            Route::delete('items/{item}', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'removeItem'])->name('remove-item');
            Route::post('{batch}/mark-ready', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'markReady'])->name('mark-ready');
            Route::delete('{batch}', [\App\Http\Controllers\Voyager\AcademyCardBatchController::class, 'destroy'])->name('destroy');
        });
        Route::get('instructor-payouts', [\App\Http\Controllers\Voyager\AcademyInstructorPayoutController::class, 'index'])->name('instructor-payouts.index');
        Route::post('instructor-payouts/{invoice}/mark-paid', [\App\Http\Controllers\Voyager\AcademyInstructorPayoutController::class, 'markPaid'])->name('instructor-payouts.mark-paid');
        Route::post('instructor-payouts/manual', [\App\Http\Controllers\Voyager\AcademyInstructorPayoutController::class, 'storeManualPayment'])->name('instructor-payouts.store-manual');
    });
    // WebPenter HR - staff document-template builder + issuance flow (NOT
    // part of the IT Academy student system - separate feature for
    // WebPenter's own staff). Same 'auth'-only-at-this-level pattern as the
    // academy admin group above; each controller's authorizeStaff() enforces
    // HR-or-Administrator.
    Route::prefix('hr-documents')->name('hr-documents.')->middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\HrDocumentsDashboardController::class, 'index'])->name('dashboard');
        Route::get('templates', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'index'])->name('templates.index');
        Route::get('templates/create', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'create'])->name('templates.create');
        Route::post('templates', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'store'])->name('templates.store');
        Route::post('templates/preview', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'preview'])->name('templates.preview');
        Route::get('templates/{template}/edit', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'edit'])->name('templates.edit');
        Route::put('templates/{template}', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'update'])->name('templates.update');
        Route::delete('templates/{template}', [\App\Http\Controllers\Voyager\DocumentTemplateController::class, 'destroy'])->name('templates.destroy');

        Route::get('issue', [\App\Http\Controllers\Voyager\DocumentIssuanceController::class, 'create'])->name('issue.create');
        Route::post('issue/preview', [\App\Http\Controllers\Voyager\DocumentIssuanceController::class, 'preview'])->name('issue.preview');
        Route::post('issue', [\App\Http\Controllers\Voyager\DocumentIssuanceController::class, 'store'])->name('issue.store');
        Route::get('issuances', [\App\Http\Controllers\Voyager\DocumentIssuanceController::class, 'index'])->name('issuances.index');
        Route::post('issuances/{issuance}/send-email', [\App\Http\Controllers\Voyager\DocumentIssuanceController::class, 'sendEmail'])->name('issuances.send-email');
    });


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

    // Single login page for existing admin staff AND Academy-only users.
    // Registered AFTER Voyager::routes() above - Laravel's route
    // collection is keyed by method+URI, so a later registration for the
    // same admin/login GET/POST replaces Voyager's own entry rather than
    // being shadowed by it. See AcademyAwareAuthController for why this
    // override exists.
    Route::get('login', [\App\Http\Controllers\Voyager\AcademyAwareAuthController::class, 'login'])->name('voyager.login');
    Route::post('login', [\App\Http\Controllers\Voyager\AcademyAwareAuthController::class, 'postLogin'])->name('voyager.postlogin');
    Route::post('logout', [\App\Http\Controllers\Voyager\AcademyAwareAuthController::class, 'logout'])->name('voyager.logout');
});
