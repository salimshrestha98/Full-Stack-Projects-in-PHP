@extends('layouts.dashboard')

@section('title', 'Ads List')

@section('head-tags')
<style>
    #robot-verify {
        background: #f0f0f0;
        padding: 25px;
    }

    ol li {
        margin-bottom: 20px;
    }

    ol img {
        max-width: 800px;
        margin-top: 15px;
    }
</style>
@endsection

@section('content')
<div id="robot-verify">
<h4 class="mb-4"><strong>Prove You're Not a Robot</strong></h4>
    <ol>
        <li>Go to Google Search Console and search for following phrases.<br>
            <img src="{{ $boost_page->phrase_img }}" alt="">
        </li>
        <li>Find the result that looks like following screenshot. Click on the result.<br>
            <img src="{{ $boost_page->serp_img }}" alt="" class="serp_img">
        </li>
        <li>Copy the redeem code (RE-xxxxx) from the bottom of the website and paste below.</li>
        <li>Click on Download. Your download will start.</li>
    </ol>
</div>
@endsection