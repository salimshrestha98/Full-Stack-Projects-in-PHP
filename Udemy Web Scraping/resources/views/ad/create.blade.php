@extends('layouts.dashboard')

@section('title', 'New Ad')

@section('content')
<div class="col-md-6 offset-md-3 border py-3">
<form action="." method="post">
@csrf
    <h3 class="py-3 mb-5 text-center bg-dark text-white">Create New Ad</h3>
    <div class="form-group">
        <label for="type"><strong>Ad Type</strong></label>
        <select name="type" id="" class="form-control">
        <option value="">---- Select Ad Type ----</option>
            <option value="banner">Banner</option>
        </select>
    </div>
    <div class="form-group">
        <label for="site"><strong>Site</strong></label>
        <select name="site" id="" class="form-control">
            <option value="">---- Select Site ----</option>
            <option value="amazon">Amazon  Affiliate</option>
            <option value="mylead">MyLead</option>
            <option value="other">Other</option>
        </select>
        <input type="text" name="other-site" id="" class="form-control mt-2" placeholder="Enter Other Site Name..">
    </div>
    <div class="form-group">
        <label for="product"><strong>Product</strong></label>
        <input type="text" name="product" id="" class="form-control">
    </div>
    <div class="form-group">
        <label for="categories"><strong>Categories</strong></label>
        <div>
            <input type="checkbox" name="categories[]" value="business" id=""> Business<br>
            <input type="checkbox" name="categories[]" value="design" id=""> Design<br>
            <input type="checkbox" name="categories[]" value="development" id=""> Development<br>
            <input type="checkbox" name="categories[]" value="finance-accounting" id=""> Finance & Accounting<br>
            <input type="checkbox" name="categories[]" value="it-software" id=""> IT & Software<br>
            <input type="checkbox" name="categories[]" value="office-productivity" id=""> Office Productivity<br>
            <input type="checkbox" name="categories[]" value="personal-development" id=""> Personal Development<br>
            <input type="checkbox" name="categories[]" value="marketing" id=""> Marketing<br>
            <input type="checkbox" name="categories[]" value="lifestyle" id=""> Lifestyle<br>
            <input type="checkbox" name="categories[]" value="photography-video" id=""> Photography & Video<br>
            <input type="checkbox" name="categories[]" value="health-fitness" id=""> Health & Fitness<br>
            <input type="checkbox" name="categories[]" value="music" id=""> Music<br>
            <input type="checkbox" name="categories[]" value="teaching-academics" id=""> Teaching & Academics<br>
        </div>
    </div>

    <div class="form-group">
        <label for="target"><strong>Target Countries</strong></label>
        <div>
            <input type="checkbox" name="target[]" value="GLOBAL" id="" checked> Global<br>
            <input type="checkbox" name="target[]" value="US" id=""> United States<br>
            <input type="checkbox" name="target[]" value="AU" id=""> Australia<br>
            <input type="checkbox" name="target[]" value="IN" id=""> India<br>
            <input type="checkbox" name="target[]" value="BR" id=""> Brazil<br>
        </div>
    </div>

    <div class="form-group">
        <label for="location"><strong>Ad Location</strong></label>
        <select name="location" id="" class="form-control">
            <option value="">---- Select Ad Location ----</option>
            <option value="header">Header</option>
            <option value="content">Main Content</option>
            <option value="sidebar">Sidebar</option>
            <option value="footer">Footer</option>
        </select>
    </div>
    <div class="form-group">
        <label for="width"><strong>Width</strong></label>
        <input type="number" name="width" id="" class="form-control" placeholder="Ad width in px">
    </div>
    <div class="form-group">
        <label for="height"><strong>Height</strong></label>
        <input type="number" name="height" id="" class="form-control" placeholder="Ad height in px">
    </div>
    <div class="form-group">
        <label for="content"><strong>Ad Remarks</strong></label>
        <textarea rows=5 name="remarks" id="" class="form-control" placeholder="Ad Remarks"></textarea>
    </div>
    <div class="form-group">
        <label for="content"><strong>Ad Content</strong></label>
        <textarea rows=10 name="content" id="" class="form-control" placeholder="Paste ad script here"></textarea>
    </div>

    <div class="d-block text-center">
    <button type="submit" class="btn btn-info">Create Ad</button>
    </div>

</form>
</div>
@endsection