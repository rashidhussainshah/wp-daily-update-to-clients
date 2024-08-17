<?php

namespace App\Widgets;

use App\Models\User;
use App\Models\UserPayment;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class UserPaymentsChart extends AbstractWidget
{
    protected $config = [];

    public function run()
    {
        $userId = request()->get('user_id');
        $query = UserPayment::query();

        if ($userId) {
            $query->where('developer_id', $userId);
        }

        // Fetching all data including payable amounts
        $data = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('sum(total_earning) as total_earning'),
            DB::raw('sum(dev_earning) as dev_earning'),
            DB::raw('sum(paid) as paid'),
            DB::raw('sum(payable) as payable')
        )
            ->groupBy('date')
            ->get();

        $users = User::all();

        return view('voyager::widgets.user_payments_chart', [
            'data' => $data,
            'users' => $users,
            'selectedUser' => $userId,
        ]);
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
