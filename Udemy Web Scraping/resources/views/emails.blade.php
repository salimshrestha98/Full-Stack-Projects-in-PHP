<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('/') }}/bootstrap-4/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/style.css">
    <link rel="stylesheet" href="{{ url('/') }}/fa/css/fontawesome-all.min.css">
    <script src="{{ url('/') }}/js/jquery-3.3.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.min.js"></script>
    <script src="{{ url('/') }}/bootstrap-4/js/bootstrap.min.js"></script>
    <title>Domain for sale - Fivescream.com</title>
    <style>
    </style>
</head>
<body>
<table class="table table-striped">
<thead>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Date</th>
</div>
@foreach($mails as $ml)
    <tr>
        <td>{{ $ml->name }}</td>
        <td>{{ $ml->email }}</td>
        <td>{{ $ml->message }}</td>
        <td>{{ $ml['created_at'] }}</td>
    </tr>
@endforeach
</body>
</html>