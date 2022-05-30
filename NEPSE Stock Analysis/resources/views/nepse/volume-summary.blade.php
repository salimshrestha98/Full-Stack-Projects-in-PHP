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

<table class="table table-striped border" id="tbl">
    <thead>
        <th>Symbol</th>
        <th>30 Day Ratio</th>
        <th>Prev Day Ratio</th>
        <th>Change</th>
        <th>Index</th>
    </thead>

    <tbody>
        @foreach( $stocks as $stock)
        @if($stock->change > 0)
            <tr>
                <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $stock->symbol }}" target="_blank">{{ $stock->symbol }}</a></td>
                <td>{{ $stock['30_vol_ratio'] }}</td>
                <td>{{ $stock->prev_vol_ratio }}</td>
                <td class="@if($stock->change < 0) bg-danger @endif">{{ $stock->change }}</td>
                <td>{{ $stock->index }}</td>
            </tr>
        @endif
        @endforeach
    </tbody>
</table>


<script>
$('#tbl').DataTable({
    searching: false,
    paging: false,
    fixedHeader: true,
    order: [[4, 'desc']]
});
$(this).DataTable();
</script>

</body>
</html>