@extends('layouts.app')

@section('title')
Search Results - Page {{ $courses->currentPage() }}
@endsection

@section('head-tags')
<meta name="Search results for {{ app('request')->input('q') }}.">
<meta name="robots" content="noindex" />
@endsection

@section('content')

<div class="container">
    <h1 class="mb-4 text-info">Search Results</h1>
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