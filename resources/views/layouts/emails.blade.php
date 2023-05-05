<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{env('APP_NAME')}} - @yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status {
            font-weight: bold;
            text-transform: uppercase;
        }
        .paid {
            color: green;
        }
        .unpaid {
            color: red;
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
