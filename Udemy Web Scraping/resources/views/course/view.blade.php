@extends('layouts.app')

@section('title')
[100% Off] {{ $course->title }} Free Download
@endsection

@section('head-tags')
    <meta name="description" content="[Download Now] Udemy-{{ $course->name }}.zip({{ $course->filesize }} GB) | [100% Off] {{ $course->title }} Free Download. {{ $course->authors }}">
@endsection

@section('content')
<h1 id="course-title">
Download <strong class="text-info">{{ $course->title }}</strong> For Free !!!
</h1>
<h5 id="course-subtitle" class="mt-3">
<span class="text-success">[ Updated 2021 ]</span> {{ $course->subtitle }}
</h5>
<p id="authors" class="mt-3">Created By: <strong>{{ $course->authors }}</strong></p>
<div class="row mt-5">
    <div class="col-md-8">
        <div class="">
            <h4>What You'll Learn</h4>
            {!! $course->wyl !!}
        </div>
    </div>
    <div class="col-md-4">
        <img src="{{ $course->img_url }}" alt="course-img" class="img-fluid img-thumbnail">
        <h4 class="mt-3">
            <a href="{{ $course->url }}" target="_blank" rel="nofollow" class=" text-white mt-4 ml-1 px-4" style="border-radius:5px;font-size:16px;background:linear-gradient(#e2e2e2,#dc143c,red)">Buy Course</a>
            <div class="mt-3">
                <script data-cfasync='false' type='text/javascript' src='//p423273.clksite.com/adServe/banners?tid=423273_829953_7&size=7'></script>
            </div>
            
        </h4>
    </div>
</div>
<div id="description" class="mt-4">
    <h4>Description</h4>
    {!! htmlspecialchars_decode($course->description) !!}
    <p>Download {{ $course->title }} for free</p>
    <p>{{ $course->title }} 100% Off</p>
    <p>Download Udemy Course {{ $course->title }} For Free</p>
    <p>Free Download {{ $course->title }}</p>
    <p>Udemy {{ $course->title }} download</p>
    <p>Free download {{ $course->title }}</p>
    <p>{{ $course->title }} 100% off</p>
    <p>Udemy {{ $course->title }} free download</p>
    <p>Free Course {{ $course->title }}</p>

    @if($course->cat_1)
        <p>{{ $course->cat_1 }} download</p>
        <p>{{ $course->cat_1 }} course free download</p>
        <p>{{ $course->cat_1 }} free download</p>
        <p>{{ $course->cat_1 }} download for free</p>
    @endif

    @if($course->cat_2)
        <p>{{ $course->cat_2 }} download</p>
        <p>{{ $course->cat_2 }} course free download</p>
        <p>{{ $course->cat_2 }} free download</p>
        <p>{{ $course->cat_2 }} download for free</p>
    @endif

    @if($course->cat_3)
        <p>{{ $course->cat_3 }} download</p>
        <p>{{ $course->cat_3 }} course free download</p>
        <p>{{ $course->cat_3 }} free download</p>
        <p>{{ $course->cat_3 }} download for free</p>
    @endif
        
    @if(strlen($course->wtcif) > 10)
        <h4 class="mt-4">Who this course is for</h4>
        {!! htmlspecialchars_decode($course->wtcif) !!}
    @endif
</div>

<div class="mt-5">
    <h4>File Details</h4>
    <strong>[Free Download]Udemy-{{ $course->name }}.zip</strong> (
    <strong>
        @if($course->filesize < 1) {{ round($course->filesize * 1024) }} MB
        @else {{ round($course->filesize, 1) }} GB
        @endif
    </strong> )<br>
    <a href="/course/download/{{ $course->name }}" class="btn btn-info btn-sm mt-2 ml-3" rel="nofollow">Download Here</a>
</div>

<div class="mt-5" id="danger-alert-box">
    <h6 class="bg-danger text-center py-2 text-white"><span>ALERT!!&nbsp;&nbsp;&nbsp;&nbsp;ALERT!!&nbsp;&nbsp;&nbsp;&nbsp;ALERT!!</span></h6>
    <h4 class="text-danger text-center py-3">YOU ARE BEING TRACKED.</h4>
    <h5 class="px-3">Your system is completely open to trackers. We got following details about you:</h5>
    <div class="row" id="user-alert">
        <div class="col-sm-5 offset-sm-1">
            <div class="p-3">
                <p id="ua-ip">IP: <strong class="text-danger">{{ $user_ip }}</strong></p>
                <p id="ua-city">City: <strong class="text-danger">{{ $user_city }}</strong></p>
                <p id="ua-country">Country: <strong class="text-danger">{{ $user_country }}</strong></p>
                <p id="ua-browser">Browser: <strong class="text-danger"></strong></p>
                <p id="ua-os">Operating System: <strong class="text-danger"></strong></p>
                <p id="ua-device">Device: <strong class="text-danger"></strong></p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="border border-danger text-danger px-4 py-3 m-3">
                <p class="mb-3">Scammers and Governments love to keep an eye on your online activities. It's never been easier to track you. They might have collected much more personal data from your device than this. Use a VPN to protect your identity leak.</p>
                <div class="row">
                    <div class="col-sm-5 offset-sm-1 text-center">
                        <a href="https://www.howtogeek.com/133680/htg-explains-what-is-a-vpn/" class="btn border border-success mt-2">Learn More</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <a href="https://www.techradar.com/vpn/best-vpn" class="btn btn-success mt-2">Try VPN</a>
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</div>

<div id="other-courses" class="mt-5">
    <div class="container mb-5">
        <h4 class="">Similar Courses</h4>
        <div class="row">
            @foreach($similar_courses as $similar_course)
                <a href="{{ url('/') }}/course/{{ $similar_course->name }}" class="col-md-3 pt-4 px-2">
                        <img src="{{ $similar_course->img_url }}" alt="" class="img-fluid">
                        <h6 class="my-3 px-2">
                            {{ $similar_course->title }}
                        </h6>
                </a>
            @endforeach
        </div>
        <h6 class="text-center mt-3">
            <a href="{{ url('/') }}/category?q={{ $course->cat_1 }}" class="btn btn-sm btn-primary text-center">See More</a>
        </h6>
    </div>
    <hr>

    <div class="container mt-5">
        <h4 class="">Random Courses</h4>
        <div class="row">
            @foreach($random_courses as $random_course)
                <a href="{{ url('/') }}/course/{{ $random_course->name }}" class="col-md-3 pt-4 px-2">
                        <img src="{{ $random_course->img_url }}" alt="" class="img-fluid">
                        <h6 class="my-3 px-2">
                            {{ $random_course->title }}
                        </h6>
                </a>
            @endforeach
        </div>
    </div>

    <hr>

    <div class="container mt-5">
        <h4 class="">Latest Courses</h4>
        <div class="row">
            @foreach($new_courses as $new_course)
                <a href="{{ url('/') }}/course/{{ $new_course->name }}" class="col-md-3 pt-4 px-2">
                        <img src="{{ $new_course->img_url }}" alt="" class="img-fluid">
                        <h6 class="my-3 px-2">
                            {{ $new_course->title }}
                        </h6>
                </a>
            @endforeach
        </div>
    </div>
</div>

</div>

@endsection