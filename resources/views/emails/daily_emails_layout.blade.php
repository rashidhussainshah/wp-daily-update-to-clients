<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('email_title')</title>
    <style>
        /* Add your styles here */
        /* For example: */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
        }
        /* Add responsive styles */
        @media only screen and (max-width: 500px) {
            .container {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        @yield('logo')
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
