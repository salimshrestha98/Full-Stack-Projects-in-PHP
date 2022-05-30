@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('head-tags')
<style>
    ul {
        padding: 0;
    }

    li {
        list-style: none;
    }

    li a {
        color: blue;
    }

    .card {
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header">Ad Controls</div>
  <div class="card-body">
    <ul>
        <li><a href="/ad/">View All Ads</a></li>
        <li><a href="/ad/create">Create Ad</a></li>
    </ul>
  </div>
</div>

<div class="card">
  <div class="card-header">Boost Page Controls</div>
  <div class="card-body">
    <ul>
        <li><a href="/boost/create">Create Boost Page</a></li>
    </ul>
  </div>
</div>


@endsection