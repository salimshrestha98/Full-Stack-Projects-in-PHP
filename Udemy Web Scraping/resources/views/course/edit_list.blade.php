@extends('layouts.app')

@section('title', 'Learn4Free - Download Premium Online Courses For Free')

@section('content')
<div>
    <form action="/course/edit" method="get">
        @csrf
        <div class="row">
            <div class="col-sm-10">
                <input type="text" name="q" id="" class="form-control" placeholder="Enter course detail">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
</div>
<hr>
<div>
    @foreach($courses as $course)
        <a href="{{ url('/') }}/course/edit/{{ $course->id }}" class="row course-item">
            <div class="col-sm-3">
                <img src="{{ $course->img_url }}" alt="" class="img-fluid img-thumbnail">
            </div>
            <div class="col-sm-9 pt-2">
                <h6>{{ $course->title }}</h6>
                <span>Created By - {{ $course->authors}}</span>
            </div>
        </a>
    @endforeach
</div>


@endsection