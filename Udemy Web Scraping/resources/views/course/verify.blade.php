@extends('layouts.single')

@section('head-tags')
<style>
#main {
    background: #f0f0f0;
}

#main img {
    margin: 25px 0 15px 0;
}

li {
    margin-bottom: 10px;
}

</style>
@endsection

@section('content')

<div class="border border-warning p-3 bg-warning col-sm-6">
    To keep our resources safe, we have to verify that you are a real human being and not a robot. Please complete the task given below.
</div>

<div id="robot-verify" class="mt-5">
    <h4 class="mb-4"><strong>Prove You're Not a Robot</strong></h4>
    <p>[Estimated Time: 20 seconds]</p>
    <ol>
        <li>Go to Google Search Console and search for any of the following phrases.<br>
            <img src="{{ $boost_page->phrase_img }}" alt="">
        </li>
        <li>Find the result that looks like following screenshot on first few pages. Click on the result.<br>
            <img src="{{ $boost_page->serp_img }}" alt="" class="serp_img">
        </li>
        <li>Copy the verification code (V-xxxxx) from the bottom of the website and paste below.</li>
        <li>Click on Download. Your download will start.</li>
    </ol>
</div>
<div class="col-sm-6 my-5 border border-dark p-2">
    <form action="/course/download/{{ $course->name }}" method="post">
    @csrf
    <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="row">
            <div class="col-sm-8">
                <input type="text" name="vcode" id="" class="form-control" placeholder="Paste Verification Code Here [V-xxxxx]">
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success">Download</button>
            </div>
        </div>
    </form>
</div>

@endsection