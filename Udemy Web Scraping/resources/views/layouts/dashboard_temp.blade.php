<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('/') }}/bootstrap-4/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/style.css">
    <link rel="stylesheet" href="{{ url('/') }}/fa/css/fontawesome-all.min.css">
    <script src="{{ url('/') }}/js/jquery-3.3.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.min.js"></script>
    <script src="{{ url('/') }}/bootstrap-4/js/bootstrap.min.js"></script>
    <title>@yield('title')</title>
    @yield('head-tags')
    <style>
        body,html {
            max-width: 100%;
            overflow-x: hidden;
        }

        #db-sidebar {
            min-height: 100vh;
        }

        #db-sidebar-links-container {

        }

        #db-sidebar-links-container li {
            list-style: none;
        }

        #db-sidebar-links-container a {
            color: #ffffff;
        }

        #db-sidebar-links-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-md-3 bg-info" id="db-sidebar">
        <div id="brand-container" class="text-center">
            <a id="brand" href="{{ url('/') }}"><img src="/favicon.ico" alt=""><span>learn4free</span></a>
        </div>
        <div class="container" id="db-sidebar-links-container">
            <ul class="border py-2">
                <li><a href="/ad">View Ads</a></li>
                <li><a href="/ad/create">Create Ad</a></li>
            </ul>
        </div>     
    </div>

    <div class="col-md-9">
        <div class="container py-5">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ url('/') }}/js/main.js"></script>



</body>
</html>