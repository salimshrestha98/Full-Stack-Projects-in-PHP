<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Today's Dojis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<table class="table table-striped table-bordered">
    <thead>
        <th>Symbol</th>
        <th>Head</th>
        <th>Body</th>
        <th>Tail</th>
        <th>Diff</th>
        <th>Index</th>
    </thead>
    <tbody>
        @foreach($doji as $doj)
            <tr>
                <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $doj[0] }}" target="_blank">{{ $doj[0] }}</a></td>
                <td>{{ $doj[1] }}</td>
                <td>{{ $doj[2] }}</td>
                <td>{{ $doj[3] }}</td>
                <td>{{ $doj[4] }}</td>
                <td>{{ $doj[5] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>