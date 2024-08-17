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
        $totalPayable = 0 ;
        if ($request->query('user_id')) {
            $totalPayable = UserPayment::getPayable($request->query('user_id'));
        }
        else {
            $totalPayable = UserPayment::currentDeveloper()->sum('payable');
        }
        $string = trans_choice('eod.total_payable', $totalPayable);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-credit-cards',
            'title'  => " {$string} {$currency} {$totalPayable}",
            'text'   => __('eod.payable_text', ['currency' => $currency, 'count' => $totalPayable]),
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
