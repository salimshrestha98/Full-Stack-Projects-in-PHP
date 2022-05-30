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
</head>
<body>
<div id="header">
    <div class="wrapper row">
        <div class="col-sm-3" id="brand-container">
            <a id="brand" href="{{ url('/') }}"><img src="/favicon.ico" alt=""><span>learn4free</span></a>
        </div>

         <div class="col-sm-6">
            <form action="{{ url('/') }}/search/" method="get" id="search-form">
                <div id="search-box-container" class="">
                    <input type="text" name="q" id="search-box" placeholder="Search">
                    <button type="submit" id="search-btn" class="bg-white"><i class="fa fa-search text-dark"></i></button>
                </div>                  
            </form>
        </div>

        <div class="col-sm-3 text-center">           
            <div class="dropdown" id="category-dropdown">
                <button class="btn btn-white dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Category
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=business">Business</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=design">Design</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=development">Development</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=finance-accounting">Finance & Accounting</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=it-software">IT & Software</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=office-productivity">Office Productivity</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=personal-development">Personal Development</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=marketing">Marketing</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=lifestyle">Lifestyle</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=photography-video">Photography & Video</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=health-fitness">Health & Fitness</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=music">Music</a>
                    <a class="dropdown-item" href="{{ url('/') }}/category?q=teaching-academics">Teaching & Academics</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main" class="py-4">
    <div class="wrapper">
        @yield('content')
    </div>
</div>

<div id="footer" class="bg-dark">
        <p class="text-center m-0 py-3 text-white">Copyright &copy; 2021  <strong class="ml-3">learn4free.fivescream.com</strong></p>
</div>

<script src="{{ url('/') }}/js/main.js"></script>



</body>
</html>