<?php

namespace App\Widgets;

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
        if ($request->query('user_id')) {
            $countPayable = UserPayment::where('developer_id',$request->query('user_id'))->sum('payable');
            $countPaid = UserPayment::where('developer_id',$request->query('user_id'))->approved()->sum('paid');

        } else {
            $countPayable = UserPayment::currentDeveloper()->sum('payable');
            $countPaid = UserPayment::currentDeveloper()->approved()->sum('paid');
        }

        $count = $countPayable - $countPaid;

        $string = trans_choice('eod.total_remaining', $count);
        $currency  = setting('admin.currency');
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-truck',
            'title'  => " {$string} {$currency} {$count}",
            'text'   => __('eod.remaining_text', ['currency' => $currency, 'count' => $count]),
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
