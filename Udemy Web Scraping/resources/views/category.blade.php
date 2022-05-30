@extends('layouts.app')

@section('title')

Learn4Free - Top {{ $category }} Courses For Free Download - Page {{ $courses->currentPage() }}

@endsection

@section('head-tags')
    <meta name="description" content="Download 1000+ Top {{ $category }} Courses Completely Free. No Registration required.">
    @if($courses->currentPage() > 1) 
        <meta name="robots" content="noindex" />
    @endif
@endsection

@section('content')

<div class="container">
    <h1 class="mb-4">Download Popular <strong class="text-info">{{ $category }}</strong> Courses Free [2021]</h1>
    @foreach($courses as $course)
        <a href="{{ url('/') }}/course/{{ $course->name }}" class="row py-4 course-item">
            <div class="col-sm-3">
                <img src="{{ $course->img_url }}" alt="" class="img-fluid img-thumbnail">
            </div>
            <div class="col-sm-9 py-2">
                <h3>{{ $course->title }}</h3>
                <span>Created By - {{ $course->authors}}</span>
            </div>
        </a>
        <hr>
    @endforeach

    <div class="float-right">{{ $courses->links() }}</div>
</div>

@endsection