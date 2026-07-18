<?php

namespace App\Widgets;

use App\Models\Expense;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class TotalRemainingDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(Request $request)
    {
        $userId = dashboardTargetUserId($request);
        $countPayable = UserPayment::getPayable($userId);
        $countPaid = UserPayment::getPaid($userId);
        $advanceGivenPayment = Expense::getAdvance($userId, Expense::IN_PKR);
        $count = ($countPayable - $advanceGivenPayment) - $countPaid;
        $advanceGivenPaymentInUsd = Expense::getAdvance($userId, Expense::IN_USD);

        $string = trans_choice('eod.total_remaining', $count);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-truck',
            'title'  => " {$string} {$currency} {$count}",
            'text'   => __('eod.remaining_text', ['currency' => $currency, 'count' => $count, 'advance' => $advanceGivenPayment, 'advance_in_usd' => $advanceGivenPaymentInUsd]),
            'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
        ] + (isAdministrator() ? [
            'button' => [
                'text' => __('eod.view_all_payments'),
                'link' => route('voyager.user-payments.index'),
            ],
        ] : [])));
    }
    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed(): bool
    {
        return canViewPaymentDimmers();
    }
}
