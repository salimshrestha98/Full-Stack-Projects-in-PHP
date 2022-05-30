<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $stock->name }} ({{ $stock->symbol }}) - Overview</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <style>
        .table th, .table td {
            font-size: 12px;
            padding: 0.2rem 0.75rem;
        }

        canvas {
            max-height: 540px;
            max-width: 100%;
            margin: 30px 0px 60px 0px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <!-- <a class="btn btn-secondary my-2" href="{{ url('/') }}">&lt;&lt;&nbsp;Go Back</a> -->
    <div class="row">
        <div class="col-md-4 jumbotron bg-white">
        <table>
            <tr><td><b> Symbol : </b></td><td> {{ $stock->symbol }}</td></tr>  
            <tr><td><b> Name : </b></td><td> {{ $stock->name }}</td></tr>
            <tr><td><b> Sector : </b></td><td> {{ $stock->sector }}</td></tr>  
            <tr><td><b> Ltp : </b></td><td> {{ $stock->ltp }}</td></tr>
            <tr><td><b> 52 High : </b></td><td> {{ $stock['52_high'] }}</td></tr>  
            <tr><td><b> 52 Low : </b></td><td> {{ $stock['52_low'] }}</td></tr>
            <tr><td><b> 120 Avg : </b></td><td> {{ $stock['120_avg'] }}</td></tr> 
            <tr><td><b> Yield : </b></td><td> {{ $stock->yield }}%</td></tr>
            <tr><td><b> Eps : </b></td><td> {{ $stock->eps }}</td></tr>
            <tr><td><b> PE Ratio : </b></td><td> {{ $stock->pe_ratio }}</td></tr>
            <tr><td><b> Book Value : </b></td><td> {{ $stock->book_value }}</td></tr>  
            <tr><td><b> PBV Ratio : </b></td><td> {{ $stock->pbv_ratio }}</td></tr>
            <tr><td><b> Dividend : </b></td><td> {{ $stock->dividend }}%</td></tr>
            <tr><td><b> Bonus : </b></td><td> {{ $stock->bonus }}%</td></tr>
            <tr><td><b> Return : </b></td><td> {{ $stock->return*100 }}%</td></tr> 
            <tr><td><b> Listed Shares : </b></td><td> {{ $stock->listed_shares }}</td></tr>
            <tr><td><b> Market Cap : </b></td><td> {{ $stock->market_cap }}</td></tr>
            <tr><td><b> 30 Day Avg Volume : </b></td><td> {{ $stock['30_day_avg_volume'] }}</td></tr>
            </table>
        </div>

        <div class="col-md-8 pt-3">
            <table class="table table-striped" id="similar-scrips-table">
                <thead>
                    <th>S.N.</th>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Bar</th>
                    <th>LTP</th>
                    <th>Yield</th>
                    <th>EPS</th>
                    <th>PE Ratio</th>
                    <th>Book Value</th>
                    <th>PBV Ratio</th>
                    <th>ROI</th>
                </thead>
                <tbody>
                @foreach($similar_stocks as $st)
                    <tr class="@if($stock->symbol == $st->symbol) bg-dark text-white @endif">
                        <td>{{ $loop->index + 1}}</td>
                        <td><a href="{{ url('/') }}/company/{{ $st->symbol }}" target="_blank" title="Company Overview">{{ $st->symbol }}</a></td>
                        <td>{{ $st->name }}</td>
                        <td style="background: {{ $st->bg }}"></td>
                        <td>{{ $st->ltp }}</td>
                        <td>{{ $st->yield }}%</td>
                        <td>{{ $st->eps }}</td>
                        <td>{{ $st->pe_ratio }}</td>
                        <td>{{ $st->book_value }}</td>
                        <td>{{ $st->pbv_ratio }}</td>
                        <td>{{ $st->return*100 }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">1 Year Yield</span></h5>
            <canvas id="yieldCanvas"></canvas>
        </div>
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">Return On Investment</h5>
            <canvas id="returnCanvas"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">EPS</h5>
            <canvas id="epsCanvas"></canvas>
        </div>
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">PE Ratio</h5>
            <canvas id="pe_ratioCanvas"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">Book Value</h5>
            <canvas id="book_valueCanvas"></canvas>
        </div>
        <div class="col-md-6">
            <h5><span class="border px-5 py-2 bg-secondary text-white">PBV Ratio</h5>
            <canvas id="pbv_ratioCanvas"></canvas>
        </div>
    </div>
</div>
<script>
@foreach($canvas_names as $n)
var {{ $n }}ctx = document.getElementById('{{ $n }}Canvas').getContext('2d');
var chart = new Chart({{ $n }}ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [
            @foreach($similar_stocks as $st)
                '{!! $st->symbol !!}',
            @endforeach
            ],
        datasets: [
        {
            label: '{{ $n }}',
            backgroundColor: [
                @foreach($backgrounds as $bg)
                    '{{ $bg }}',
                @endforeach
            ],
            data: [
            @foreach($similar_stocks as $st)
                '{{ $st[$n] }}',
            @endforeach
            ]
        }
        ]
    },

    // Configuration options go here
    options: {}
});
@endforeach

$('#similar-scrips-table').DataTable({
    paging: false,
    fixedHeader: true
});
$(this).DataTable();

</script>
</body>
</html>