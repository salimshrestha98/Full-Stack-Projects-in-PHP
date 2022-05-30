<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Similar Candles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<div class="container">
    <form action="/nepse/find" method="post" class="form">
        @csrf
        <input type="date" name="date" value="" id="">
        <input type="submit" value="Find Now">
    </form>
    <br>
    <form action="/nepse/find" method="post" class="form">
        @csrf
        <input type="text" name="code" value="" placeholder="Code Here">
        <input type="submit" value="Find Now">
    </form>
    <h1 class="mt-5">L3 Candles</h1>
    <table class="table table-striped my-5">
        <thead>
            <th>Date</th>
            <th>Day</th>
            <th>Prev_2</th>
            <th>Prev_1</th>
            <th>Code</th>
            <th>Next_1</th>
            <th>Next_2</th>
        </thead>
        <tbody>
            @foreach($l3 as $r)
                <tr>
                    <td>{{ $r->date }}</td>
                    <td>{{ $r->day }}</td>
                    <td>{{ $r['prev_2'] }}</td>
                    <td>{{ $r['prev_1'] }}</td>
                    <td>{{ $r->code }}</td>
                    <td>{{ $r['next_1'] }}</td>
                    <td>{{ $r['next_2'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</body>
</html>