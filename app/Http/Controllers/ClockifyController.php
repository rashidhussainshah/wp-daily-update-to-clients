<?php

namespace App\Http\Controllers;

use App\Services\ClockifyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClockifyController extends Controller
{
    public function getTodayEntries(Request $request)
    {
        $user = Auth::user();

        if (!$user->clockify_user_id) {
            return response()->json(['error' => 'Clockify user ID not found.'], 404);
        }

        $clockifyService = app(ClockifyService::class);
        $startDate = Carbon::today();
        $endDate = Carbon::now();

        try {
            $timeEntries = $clockifyService->getUserTimeEntries($user->clockify_user_id, $startDate, $endDate);

            if (empty($timeEntries)) {
                return response()->json(['entries' => 'No time entries found for today.']);
            }

            $formattedEntries = [];
            foreach ($timeEntries as $entry) {
                $description = $entry['description'] ?? 'No description';
                $start = Carbon::parse($entry['timeInterval']['start']);
                $end = Carbon::parse($entry['timeInterval']['end']);
                $durationMinutes = $start->diffInMinutes($end);

                $hours = intdiv($durationMinutes, 60);
                $minutes = $durationMinutes % 60;

                $formattedEntries[] = $description . $hours . 'h ' . $minutes . 'm';
            }

            return response()->json(['entries' => implode("\n", $formattedEntries)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve time entries: ' . $e->getMessage()], 500);
        }
    }
}
