@extends('layouts.app')

@section('title', 'Learn4Free - Download Premium Online Courses For Free')

@section('content')
<div class="container">
    <h2>Editing <strong class="text-info">{{ $course->title }}</strong></h2>
    <img src="{{ $course->img_url }}" alt="" class="mt-4">
    <p>{{ $course->authors }}</p>
    <form action="/course/update" method="post" class="form mt-5">
        @method('PUT')
        @csrf
        <input type="hidden" name="id" value="{{ $course->id }}">
        <div class="row form-group">
            <div class="col-sm-3">Filename</div>
            <div class="col-sm-9">
                <input type="text" name="filename" value="{{ $course->filename}}" class="form-control" id="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">Filesize</div>
            <div class="col-sm-9">
                <input type="text" name="filesize" value="{{ $course->filesize}}" class="form-control" id="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">File URL</div>
            <div class="col-sm-9">
                <input type="text" name="file_url" value="{{ $course->file_url}}" class="form-control" id="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">Torrent URL</div>
            <div class="col-sm-9">
                <input type="text" name="torrent_url" value="{{ $course->torrent_url}}" class="form-control" id="">
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mt-5">Update</button>
    </form>
</div>

@endsection