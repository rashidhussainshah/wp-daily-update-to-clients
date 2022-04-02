<?php



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
if (!function_exists('readableCurrentDate')) {
    function readableCurrentDate(): string
    {
        return \Carbon\Carbon::now()->toFormattedDateString();
    }
}
