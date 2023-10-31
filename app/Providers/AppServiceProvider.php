<?php

namespace App\Providers;

use App\Actions\MarkUserPaymentPaidDetailAction;
use App\Actions\ViewUserPaymentDetailAction;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Voyager::addAction(ViewUserPaymentDetailAction::class);
        Voyager::addAction(MarkUserPaymentPaidDetailAction::class);
    }
}
