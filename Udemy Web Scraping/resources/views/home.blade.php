@extends('layouts.app')

@section('title')

@if($courses->currentPage() == 1) 
    Learn4Free - Download 100000+ Premium Online Courses Completely Free
@else
    Learn4Free - Download 100000+ Premium Online Courses For Free - Page {{ $courses->currentPage() }}
@endif

@endsection

@section('head-tags')
    <meta name="description" content="Browse from our catalog of 100,000+ free online courses. Download and learn any course for free. No registration.">
    @if($courses->currentPage() > 1) 
        <meta name="robots" content="noindex" />
    @endif
@endsection

@section('content')
<h1>Download Latest Online Courses in HD free. [100% Off Udemy Courses]</h1>
<div class="border border-dark mt-4 mb-2 p-3 text-dark">   
    <h5 class="">Learn4Free is a platform to provide learning materials free of cost.</h5>
    <ul class="mt-1"
        <li>Enjoy premium udemy courses for free.</li>
        <li>Learn anything you like anytime.</li>
        <li>Easy to Download.</li>
        <li>No Registration Required</li>
        <li>No Card Required</li>
    </ul>
        If you find these materials valuable, please consider buying the course on their original platforms to support the creators.
</div>
<h4 class="mt-5 mb-3 text-secondary">Most Popular</h4>
@foreach($courses as $course)
    <a href="{{ url('/') }}/course/{{ $course->name }}" class="row py-2 course-item">
        <div class="col-sm-4">
            <img src="{{ $course->img_url }}" alt="" class="img-fluid img-thumbnail">
        </div>
        <div class="col-sm-8 py-2">
            <h3>{{ $course->title }}</h3>
            <span>Created By - {{ $course->authors}}</span>
        </div>
    </a>
    <hr>
@endforeach

<div class="float-right">{{ $courses->links() }}</div>

<div class="container mt-5 pt-5">
    <h4 class="mt-5">Random Courses</h4>
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

<div class="container mt-5 pt-5">
    <h4 class="mt-5">Latest Courses</h4>
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

@endsection