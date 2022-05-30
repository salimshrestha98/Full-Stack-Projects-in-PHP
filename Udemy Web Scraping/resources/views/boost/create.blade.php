@extends('layouts.dashboard')

@section('title', 'New Ad')

@section('content')
<div class="col-md-6 offset-md-3 border py-3">
<form action="." enctype="multipart/form-data" method="post">
@csrf
    <h3 class="py-3 mb-5 text-center bg-dark text-white">Create New Boost Page</h3>
    <div class="form-group">    
        <input type="text" name="url" id="" class="form-control" placeholder="Boost Page Url... [https://example.com]">
    </div>
    <div class="form-group">    
        <input type="text" name="phrases" id="" class="form-control" placeholder="Search Phrases... [Phrase 1 | Phrase 2]">
    </div>
    <div class="form-group">
            <label for="serp_url"><strong>SERP Image  : </strong></label>
        <input type="file" name="serp_url" id="">
    </div>
    <div class="form-group">    
        <input type="text" name="location" id="" class="form-control" placeholder="Location... [BR,IN,GLOBAL]">
    </div>
    <div class="form-group">    
        <button class="btn btn-primary btn-sm" type="submit">Add Page</button>
    </div>
</form>
</div>
@endsection