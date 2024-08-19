<?php

namespace App\Widgets;

use App\Models\User;
use App\Models\UserPayment;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class TopEarnersChart extends AbstractWidget
{
    protected $config = [];

    public function run()
    {
        $topUsers = UserPayment::select(
            'developer_id',
            DB::raw('sum(dev_earning) as total_earnings')
        )
            ->groupBy('developer_id')
            ->orderBy('total_earnings', 'desc')
            ->limit(10)
            ->get();

        $userIds = $topUsers->pluck('developer_id');
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $chartData = $topUsers->map(function ($item) use ($users) {
            return [
                'name' => $users[$item->developer_id]->name ?? 'Unknown',
                'total_earnings' => $item->total_earnings,
            ];
        });
        return view('voyager::widgets.top_earners_chart', [
            'chartData' => $chartData,
        ]);
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return isAdministrator();
    }
}
