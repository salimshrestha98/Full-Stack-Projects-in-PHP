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

    <style>
        .table th, .table tr, .table td {
            font-size: 12px;
            padding: 5px;
            margin: 0;
        }
    </style>
</head>
<body>
<table class="table table-striped table-bordered">
    <thead>
        <th>Symbol</th>
        <th>Doji Type</th>
    </thead>
    <tbody>
        @foreach($doji as $doj)
            <tr>
                <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $doj[0] }}" target="_blank">{{ $doj[0] }}</a></td>
                <td>
                    @if( $doj[1] < 1 ) Perfect Hammer
                    @elseif( $doj[1] < 5 ) Long Hammer
                    @elseif( $doj[1] < 33) Hammer
                    @elseif( $doj[3] < 33 ) Inverted Hammer
                    @elseif( $doj[3] < 5 ) Long Inverted Hammer
                    @elseif( $doj[3] < 1 ) Perfect Inverted Hammer
                    @else Doji Star
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>