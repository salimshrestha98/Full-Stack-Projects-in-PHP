@extends('layouts.app')

@section('title')
[100% Off] {{ $course->title }} Free Coupon
@endsection

@section('head-tags')
    <meta name="description" content="[100% Off][Updated] Coupon for {{ $course->title }}. Enter the coupon code FREE1..">
@endsection

@section('content')
<h1 id="course-title">
[100% Off] Coupon <strong class="text-info">{{ $course->title }}</strong> For Free !!!
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
    <p>{{ $course->title }} for free</p>
    <p>{{ $course->title }} free</p>
    <p>{{ $course->title }} 100% Off</p>
    <p>{{ $course->title }} 100% Off Coupon</p>
    <p>Udemy Course {{ $course->title }} For Free</p>
    <p>Free Coupon {{ $course->title }}</p>
    <p>Udemy {{ $course->title }} download coupon</p>
    <p>Free Coupon {{ $course->title }}</p>
    <p>{{ $course->title }} 100% off</p>
    <p>Udemy {{ $course->title }} Free Coupon</p>
    <p>{{ $course->title }} Free Coupon</p>
    <p>Free Course {{ $course->title }}</p>
        
    @if(strlen($course->wtcif) > 10)
        <h4 class="mt-4">Who this course is for</h4>
        {!! htmlspecialchars_decode($course->wtcif) !!}
    @endif
</div>

<div class="mt-5">
    <h4>Coupon Details</h4>
    <strong>Coupon Code : </strong> <span class="text-success">{{ $coupon }}</span> <br>
    @if($coupon_health > 50)
    <strong>Coupon Health : <span class="text-success">{{ $coupon_health }}%<span></strong> <br>
    @else
    <strong>Coupon Health : <span class="text-danger">{{ $coupon_health }}%<span></strong> <br>
    @endif
    <a href="https://udemy.com/course/{{ $course->name }}?couponCode={{ $coupon }}" class="btn btn-info btn-sm mt-2" rel="nofollow">Redeem Now</a>
</div>

@endsection