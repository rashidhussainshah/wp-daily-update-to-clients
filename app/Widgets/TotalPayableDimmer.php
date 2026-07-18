<?php

namespace App\Widgets;

use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class TotalPayableDimmer extends BaseDimmer
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
        $totalPayable = UserPayment::getPayable($userId);
        $string = trans_choice('eod.total_payable', $totalPayable);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-credit-cards',
            'title'  => " {$string} {$currency} {$totalPayable}",
            'text'   => __('eod.payable_text', ['currency' => $currency, 'count' => $totalPayable]),
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
