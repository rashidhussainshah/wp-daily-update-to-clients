<?php


use App\Models\Expense;
use App\Models\Income;
use App\Models\StudentFee;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Determine if the logged-in user is an administrator.
 *
 * @return bool
 */
if (!function_exists('isAdministrator')) {
    function isAdministrator()
    {
        $user = Auth::user();

        // Get the administrator role ID from settings
        $adminRoleId = setting('admin.administrator_role_id');

        // Check if the user has the administrator role using the role_id
        if (isset($user->role_id) && $user->role_id == $adminRoleId) {
            return true;
        }

        // Check if the user has the administrator role using the roles relation
        if (isset($user->roles) && $user->roles()->where('id', $adminRoleId)->exists()) {
            return true;
        }

        return false;
    }
}


if (!function_exists('d')) {
    function d($data, $exit = true)
    {
        echo '<pre>';
        if (is_array($data) || is_object($data)) {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        if ($exit) {
            exit;
        }
    }
}

if (!function_exists('getExpenseDetails')) {

    function getExpenseDetails(): string
    {
        $user1Administrator = User::where('email', 'rashid.bukhari78600@gmail.com')->first();
        $user2Administrator = User::where('email', 'zaars59208@gmail.com')->first();
        $totalUSD = Expense::where('amount_in', 'usd')->sum('amount');
        $totalPKR = Expense::where('amount_in', 'pkr')->sum('amount');
        if ($user1Administrator && $user2Administrator) {
            $totalUSDOfUser1 = Expense::where('amount_in', 'usd')->where('user_id', $user1Administrator->id)->sum('amount');
            $totalUSDOfUser2 = Expense::where('amount_in', 'usd')->where('user_id', $user2Administrator->id)->sum('amount');
            $totalPkrOfUser1 = Expense::where('amount_in', 'pkr')->where('user_id', $user1Administrator->id)->sum('amount');
            $totalPkrOfUser2 = Expense::where('amount_in', 'pkr')->where('user_id', $user2Administrator->id)->sum('amount');
            return "<strong>Total Expense (USD):</strong> <strong>{$totalUSD}</strong>, Total Expense (PKR): <strong>{$totalPKR}</strong>, <strong>{$user1Administrator->name}</strong> USD Expense, <strong>{$totalUSDOfUser1}</strong> PKR Expense <strong>{$totalPkrOfUser1} </strong>, <strong>{$user2Administrator->name}</strong> USD Expenses <strong>{$totalUSDOfUser2}</strong> PKR Expenses <strong>{$totalPkrOfUser2}</strong>";
        }
    }
}
if (!function_exists('getIncomeDetails')) {

    function getIncomeDetails(): string
    {
        $user1Administrator = User::where('email', 'rashid.bukhari78600@gmail.com')->first();
        $user2Administrator = User::where('email', 'zaars59208@gmail.com')->first();
        $totalUSD = Income::where('amount_in', 'usd')->sum('amount');
        $totalPKR = Income::where('amount_in', 'pkr')->sum('amount');
        if ($user1Administrator && $user2Administrator) {
            $totalUSDOfUser1 = Income::where('amount_in', 'usd')->where('user_id', $user1Administrator->id)->sum('amount');
            $totalUSDOfUser2 = Income::where('amount_in', 'usd')->where('user_id', $user2Administrator->id)->sum('amount');
            $totalPkrOfUser1 = Income::where('amount_in', 'pkr')->where('user_id', $user1Administrator->id)->sum('amount');
            $totalPkrOfUser2 = Income::where('amount_in', 'pkr')->where('user_id', $user2Administrator->id)->sum('amount');
            return "<strong>Total Income (USD):</strong> <strong>{$totalUSD}</strong>, Total Income (PKR): <strong>{$totalPKR}</strong>, <strong>{$user1Administrator->name}</strong> have <strong>{$totalUSDOfUser1} USD</strong> and <strong>{$totalPkrOfUser1} PKR</strong>, <strong>{$user2Administrator->name}</strong> have <strong>{$totalUSDOfUser2} USD</strong> and <strong>{$totalPkrOfUser2} PKR</strong>";
        }
    }
}
if (!function_exists('getRemainingAmounts')) {
    function getRemainingAmounts()
    {
        // Define the status values
        $statuses = ['pending', 'over_due'];

        // Retrieve the total, current month, and pending remaining amounts
        $totalRemaining = StudentFee::whereIn('status', $statuses)->sum('amount');
        $currentMonthRemaining = StudentFee::currentMonth()->whereIn('status', $statuses)->sum('amount');
        $pendingExceptCurrentMonth = StudentFee::where('status', 'pending')
            ->whereYear('date', '!=', now()->year)
            ->orWhere(function ($query) {
                $query->whereMonth('date', '!=', now()->month)
                    ->whereYear('date', now()->year);
            })
            ->sum('amount');

        return [
            'total' => $totalRemaining,
            'currentMonth' => $currentMonthRemaining,
            'pendingExceptCurrentMonth' => $pendingExceptCurrentMonth,
        ];
    }
}
if (!function_exists('readableCurrentDate')) {
    function readableCurrentDate(): string
    {
        return \Carbon\Carbon::now()->toFormattedDateString();
    }
}
if (!function_exists('getRandomQuote')) {
    function getRandomQuote()
    {
        $quotes = Config::get('motivationalQuotes');
        return Arr::random($quotes);
    }
}

if (!function_exists('getMailFromAddress')) {
    function getMailFromAddress(): string
    {
        return setting('email-configuration.from') ?? '';
    }
}
