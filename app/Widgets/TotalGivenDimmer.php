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

class TotalGivenDimmer extends BaseDimmer
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
        $paidThroughUserPayment = 0;
        if ($request->query('user_id')) {
            $paidThroughUserPayment = UserPayment::getPaid($request->query('user_id'));
            $advanceGivenPayment = Expense::where('developer_id',$request->query('user_id'))->where('purpose', Expense::CREDIT_TO_DEV_STATUS)->where('amount_in', Expense::IN_PKR)->sum('amount'); // payment that given advance
            $advanceGivenPaymentInUsd = Expense::where('developer_id',$request->query('user_id'))->where('purpose', Expense::CREDIT_TO_DEV_STATUS)->where('amount_in', Expense::IN_USD)->sum('amount'); // payment that given advance
        }
        else {
            $paidThroughUserPayment = UserPayment::currentDeveloper()->approved()->sum('payable');
            $advanceGivenPayment = Expense::currentDeveloper()->where('purpose', Expense::CREDIT_TO_DEV_STATUS)->where('amount_in', Expense::IN_PKR)->sum('amount'); // payment that given advance
            $advanceGivenPaymentInUsd = Expense::currentDeveloper()->where('purpose', Expense::CREDIT_TO_DEV_STATUS)->where('amount_in', Expense::IN_USD)->sum('amount'); // payment that given advance

        }
        $totalPaid = $paidThroughUserPayment + $advanceGivenPayment;
        $string = trans_choice('eod.total_paid', $totalPaid);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-check',
            'title'  => " {$string} {$currency} {$totalPaid}",
            'text'   => __('eod.paid_text', ['currency' => $currency, 'count' => $totalPaid, 'advance' => $advanceGivenPayment, 'advance_in_usd' => $advanceGivenPaymentInUsd]),
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
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Voyager::model('Post'));
    }
}
