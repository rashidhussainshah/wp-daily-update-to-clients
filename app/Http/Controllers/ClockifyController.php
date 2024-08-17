<?php

namespace App\Http\Controllers;

use App\Services\ClockifyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClockifyController extends Controller
{
    public function getTodayEntries(Request $request)
    {
        $user = Auth::user();

        if (!$user->clockify_user_id) {
            return response()->json(['error' => 'Clockify user ID not found.'], 404);
        }

        $clockifyService = app(ClockifyService::class);
        // Format the start and end dates to the required UTC format
        $startDate = Carbon::today()->startOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T00:00:00Z';
        $endDate = Carbon::today()->endOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T23:59:59Z';

        // Log the formatted dates
        Log::info('Fetching time entries from Clockify', [
            'user_id' => $user->clockify_user_id,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        try {
            $timeEntries = $clockifyService->getUserTimeEntries($user->clockify_user_id, $startDate, $endDate);

            if (empty($timeEntries)) {
                return response()->json(['entries' => 'No time entries found for today.']);
            }
            // Format the time entries
            $formattedEntries = [];
            foreach ($timeEntries as $entry) {
                $description = $entry['description'] ?? 'No description';
                $start = Carbon::parse($entry['timeInterval']['start']);
                $end = Carbon::parse($entry['timeInterval']['end']);
                $durationSeconds = $start->diffInSeconds($end);
                $hours = intdiv($durationSeconds, 3600);
                $minutes = intdiv($durationSeconds % 3600, 60);
                $seconds = $durationSeconds % 60;

                // Format time spent based on hours, minutes, and seconds
                $timeSpent = '';
                if ($hours > 0) {
                    $timeSpent .= $hours . 'h ';
                }
                if ($minutes > 0) { // Include minutes if hours are present or minutes alone
                    $timeSpent .= $minutes . 'm ';
                }
                if ($seconds > 0) { // Include seconds if minutes or hours are present
                    $timeSpent .= $seconds . 's';
                }

                $formattedEntries[] = $description . ' | ' . $timeSpent;
            }

            return response()->json(['entries' => implode("\n", $formattedEntries)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve time entries: ' . $e->getMessage()], 500);
        }
    }
}
