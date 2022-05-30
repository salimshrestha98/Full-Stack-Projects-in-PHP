@extends('layouts.dashboard')

@section('title', 'Ads List')

@section('head-tags')
<style>
    .ad-script-container {
        font-size: 10px;
        width: 100%;
    }
</style>
@endsection

@section('content')

<table class="table table-striped">
    <thead>
        <th>Ad Id</th>
        <th>Type</th>
        <th>Site</th>
        <th>Product</th>
        <th>Categories</th>
        <th>Location</th>
        <th>Size</th>
    </thead>
    <tbody>
    @foreach($ads as $ad)
        <tr>
            <td>{{ $ad->id }}</td>
            <td>{{ $ad->type }}</td>
            <td>{{ $ad->site }}</td>
            <td>{{ $ad->product }}</td>
            <td><textarea class="ad-script-container" rows=5>{!! $ad->content !!}</textarea></td>
            <td>{{ $ad->location }}</td>
            <td>{{ $ad->width }}x{{ $ad->height }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection