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
        $advanceGivenPayment = 0;
        $advanceGivenPaymentInUsd = 0;
        $countPaid = 0;
        $count = 0;
        if ($request->query('user_id')) {
            $countPayable = UserPayment::getPayable($request->query('user_id'));
            $countPaid = UserPayment::getPaid($request->query('user_id'));
            $advanceGivenPayment = Expense::getAdvance($request->query('user_id'), Expense::IN_PKR);
            $count = ($countPayable - $advanceGivenPayment ) - $countPaid;
            $advanceGivenPaymentInUsd = Expense::getAdvance($request->query('user_id'), Expense::IN_USD);
        }
        else {
            $loggedInUserId = Auth::id();
            $countPayable = UserPayment::getPayable($loggedInUserId);
            $countPaid = UserPayment::getPaid($loggedInUserId);
            $advanceGivenPayment = Expense::getAdvance($loggedInUserId, Expense::IN_PKR);
            $count = ( $countPayable - $advanceGivenPayment ) - $countPaid;
            $advanceGivenPaymentInUsd = Expense::getAdvance($loggedInUserId, Expense::IN_USD);

        }


        $string = trans_choice('eod.total_remaining', $count);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-truck',
            'title'  => " {$string} {$currency} {$count}",
            'text'   => __('eod.remaining_text', ['currency' => $currency, 'count' => $count, 'advance' => $advanceGivenPayment, 'advance_in_usd' => $advanceGivenPaymentInUsd]),
            'button' => [
                'text' => __('eod.view_all_payments'),
                'link' => route('voyager.user-payments.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }
    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed(): bool
    {
        return isBusinessPartners();
    }
}
