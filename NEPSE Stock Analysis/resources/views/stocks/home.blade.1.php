<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stocka | Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <style>
        body,table {
            max-width: 100%;
        }

        #top-row {
            font-size: 12px;
        }

        #top-row p {
            border-bottom: 1px solid;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .table th, .table td {
            font-size: 12px;
            padding: 0.2rem 0.75rem;
        }

        canvas {
            max-height: 540px;
            max-width: 100%;
        }
    </style>
</head>
<body>

<div class="container-fluid">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top border-bottom">
  <a class="navbar-brand mr-5" href="#">Stocka</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mx-3">
        <a class="nav-link" href="#sectors">Sectors</a>
      </li>
      <li class="nav-item mx-3">
        <a href="#ext" class="nav-link" id="sites-link">Sites</a>
      </li>
      </ul>
      <ul id="top-btns" class="float-right list-inline my-2">
      <li class="list-inline-item">
        <a href="/portfolio" class="btn btn-sm btn-success mx-3">Portfolio</a>
      </li>
      <li class="list-inline-item">
        <form action="." method="post">
        @csrf
            <input type="hidden" name="p" value="@samalamadumalama..">
            <input type="hidden" name="action" value="update">
            <input type="submit" value="Update" class="btn btn-sm btn-primary mx-3">
        </form>
      </li>
      <li class="list-inline-item">
        <form action="." method="post">
        @csrf
            <input type="hidden" name="p" value="@samalamadumalama..">
            <input type="hidden" name="action" value="reset">
            <input type="submit" value="Reset" class="btn btn-sm btn-danger mx-3">
        </form>
      </li>

      <li class="list-inline-item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <input type="submit" value="{{ __('Logout') }}" class="btn btn-sm btn-secondary mx-3">
        </form>
       </li>
    </ul>
  </div>
</nav>
</div>

<div class="my-5 py-1"></div>

<div class="mb-3">
    <strong class="mr-2 btn btn-sm btn-primary">Average PE : {{ round($nepse_avg['pe_ratio']) }}</strong>
    <strong class="ml-2 btn btn-sm btn-primary">Average PBV : {{ round($nepse_avg['pbv_ratio']) }}</strong>
    <strong class="ml-2 btn btn-sm btn-primary">Date : {{ $last_date }}</strong>
</div>

<div class="row" id="top-row">
    <div class="col-md-4">
        <div class="row" id="top-picks">
    
            @foreach($top_scr as $key => $ts)
            @if(count($ts) > 4)
            <div class="col-md-4 p-0 border">
            <b><p class="my-1 mx-3">{{ $key }}</p></b>
            <ol>
            @foreach($ts as $s)
                <li class="text-sm"><a href="{{ url('/') }}/company/{{ $s }}" target="_blank" title="{{ $stocks[$s]['name'] }}" class="@if(array_key_exists($s, $top_intersections)) text-warning font-weight-bold @elseif(array_key_exists($s, $top_losers))  text-danger font-weight-bold @endif">{{ $s }}</a></li>
            @endforeach
            </ol>
            </div>
            @endif           
            @endforeach
    
        </div>
    </div>

    <div class="col-md-4">
    <div class="row">
        <div class="col-md-6 border">
            <b><p class="my-1 mx-3">Top Gainers</p></b>
            <ol>
            @foreach( $top_gainers as $symbol => $change)
                <li><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $symbol }}" title="@isset($stocks[$symbol]) {{ $stocks[$symbol]->name }} ({{ $stocks[$symbol]['sector'] }}) @endisset " target="_blank" class="@if(array_key_exists($symbol, $top_intersections)) text-warning font-weight-bold @endif">{{ $symbol }}</a> <span class="float-right">{{ $change }}</span></li>
            @endforeach
            </ol>
        </div>

        <div class="col-md-6 border">
            <b><p class="my-1 mx-3">Top Transactions</p></b>
            <ol>
            @foreach( $top_transactions as $symbol => $change)
                <li><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $symbol }}" title="@isset($stocks[$symbol]) {{ $stocks[$symbol]->name }} ({{ $stocks[$symbol]['sector'] }}) @endisset" target="_blank" class="@if(array_key_exists($symbol, $top_intersections)) text-warning font-weight-bold @endif">{{ $symbol }}</a> <span class="float-right">{{ $change }}</span></li>
            @endforeach
            </ol>
        </div>
    </div>
    </div>

    <div class="col-md-4">
        <iframe src="{{ url('/') }}/volume?summary=true" frameborder="0" width="100%" height="100%"></iframe>
    </div>
</div>


<div class="mt-5"></div>

<hr>

<table class="table table-striped table-bordered mb-5" style="width:100%" id="f-list">
    <thead>
        <th>Sn.</th>
        <th>Symbol</th>
        <th>Name</th>
        <th>Sector</th>
        <th>LTP</th>
        <th>LTP - SA</th>
        <th>52H - LTP</th>
        <th>Yield</th>
        <th>PE Ratio</th>
        <th>PBV Ratio</th>
        <th>ROI</th>
        <th>Index</th>
        <th>Sect Price</th>
        <th>SP - LTP</th>
        <th>Graham Price</th>
        <th>GP - LTP</th>
    </thead>
    <tbody>
    @foreach($latest_stocks as $st)
        <tr class="@if($stocks[$st->symbol]['index_1'] < 1) bg-danger @endif">
            <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $st->symbol }}" target="_blank" title="Merolagani">{{ $loop->index+1 }}</a></td>
            <td><a href="{{ url('/') }}/company/{{ $st->symbol }}" target="_blank" title="Company Overview" class="@if(array_key_exists($st->symbol, $top_intersections)) text-warning font-weight-bold @endif">{{ $st->symbol }}</a></td>
            <td><a href="https://bizmandu.com/content/company/{{ $st->symbol }}" target="_blank" title="Bizmandu">{{ $stocks[$st->symbol]['name'] }}</a></td>
            <td><a href="#{{ str_replace(' ','-',trim($stocks[$st->symbol]['sector'])) }}" title="Goto">{{ $stocks[$st->symbol]['sector'] }}</a></td>
            <td>{{ $stocks[$st->symbol]['ltp'] }}</td>
            <td class="
            @if($stocks[$st->symbol]['ltp-sa'] < 5) bg-success 
            @elseif($stocks[$st->symbol]['ltp-sa'] < 25) bg-warning
            @else bg-danger
            @endif
            
            ">{{ $stocks[$st->symbol]['ltp-sa'] }}%</td>
            <td>{{ round(($stocks[$st->symbol]['52_high']-$stocks[$st->symbol]['ltp'])/$stocks[$st->symbol]['ltp'],2)*100 }}%</td>
            <td class="@if($stocks[$st->symbol]['yield'] > $sector_avg[$stocks[$st->symbol]['sector']]['yield'] || $stocks[$st->symbol]['yield'] > 100) bg-success @endif" title="1 Year Yield">{{ $stocks[$st->symbol]['yield'] }}</td>
            
            <td class="
            @if($stocks[$st->symbol]['pe_ratio'] < $sector_avg[$stocks[$st->symbol]['sector']]['pe_ratio'] && $stocks[$st->symbol]['pe_ratio'] > 0  && $stocks[$st->symbol]['pe_ratio'] < 40) bg-success 
            @elseif( $stocks[$st->symbol]['pe_ratio'] > 40 || $stocks[$st->symbol]['pe_ratio'] < 0) bg-danger
            @else bg-warning
            @endif
            " title="PE Ratio">{{ $stocks[$st->symbol]['pe_ratio'] }}</td>
            
            <td class="
            @if( $stocks[$st->symbol]['pbv_ratio'] > 0 && $stocks[$st->symbol]['pbv_ratio'] < 3) bg-success 
            @elseif( $stocks[$st->symbol]['pbv_ratio'] > 5 && $stocks[$st->symbol]['pbv_ratio'] < $sector_avg[$stocks[$st->symbol]['sector']]['pbv_ratio']) bg-primary
            @elseif( $stocks[$st->symbol]['pbv_ratio'] < 0 ||  $stocks[$st->symbol]['pbv_ratio'] > 5) bg-danger
            @else bg-warning
            @endif
            " title="PBV Ratio">{{ $stocks[$st->symbol]['pbv_ratio'] }}</td>
            <td title="Return On Investment">{{ $stocks[$st->symbol]['return']*100 }}%</td>
            <td title="Index 1">{{ $stocks[$st->symbol]['index_1'] }}</td>
            <td>{{ $stocks[$st->symbol]['sp'] }}</td>
            <td class="@if( $stocks[$st->symbol]['sp-ltp'] < 100) bg-success @endif">{{ $stocks[$st->symbol]['sp-ltp'] }}%</td>
            <td title="Graham Price">{{ $stocks[$st->symbol]['graham_price'] }}</td>
            <td title="GP - LTP" class="@if($stocks[$st->symbol]['ltp'] < $stocks[$st->symbol]['graham_price']*1.1) bg-success @elseif($stocks[$st->symbol]['ltp'] > $stocks[$st->symbol]['graham_price']*1.3) bg-danger text-white @else bg-warning @endif">{{ round(($stocks[$st->symbol]['ltp']/$stocks[$st->symbol]['graham_price'])*100) - 100 }}%</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h2>Sector Analysis</h2>

<canvas id="topCanvas" class="mt-5 pt-5"></canvas>
<div class="container-fluid">
<span id="ext"></span>
    <div class="row">
        <div class="col-md-6">
            <iframe src="" frameborder="0" width="100%" height="520px" id="ifr-1"></iframe>
        </div>
        <div class="col-md-6">
            <iframe src="" frameborder="0" width="100%" height="520px" id="ifr-2"></iframe>
        </div>
    </div>
</div>

<script>

$('table').each(function() {
    $(this).DataTable({
        paging: false,
        fixedHeader: true
    });
    $(this).DataTable();
})

$('#stock-table').DataTable( {
    paging: false,
    fixedHeader: true
} );

var highlightRows = [];

$('#stock-table').DataTable();
$('tr').on("click",function(e) {
    if(highlightRows.length && !e.ctrlKey) {
        for(var i=0;i<highlightRows.length;i++) {
            $(highlightRows[i]).removeClass('bg-dark text-white');
        }
        highlightRows.length = 0;
    }
    highlightRows.push($(this));
    $(this).toggleClass('bg-dark text-white');
});

$('#sites-link').click(function() {
    $('#ifr-1').attr("src","https://merolagani.com/MarketSummary.aspx");
    $('#ifr-2').attr("src","https://sharesansar.com/");
})

</script>

<script>

var ctx = document.getElementById('topCanvas').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [
            @foreach($sector_avg as $st_name => $st_avg)
                '{!! $st_name !!}',
            @endforeach
            ],
        datasets: [
        {
            label: 'PE',
            backgroundColor: '#2E3E7F',
            data: [
            @foreach($sector_avg as $st_avg)
                '{{ 10 * $st_avg['pe_ratio'] }}',
            @endforeach
            ]
        },
        {
            label: 'PBV',
            backgroundColor: '#5FD9D7',
            data: [
            @foreach($sector_avg as $st_avg)
                '{{ 100 * $st_avg['pbv_ratio'] }}',
            @endforeach
            ]
        },
        {
            label: 'PE*PBV',
            backgroundColor: '#43A7F0',
            data: [
            @foreach($sector_avg as $st_avg)
                '{{ $st_avg['pe_ratio']*$st_avg['pbv_ratio'] }}',
            @endforeach
            ]
        }
        ]
    },

    // Configuration options go here
    options: {}
});

@foreach($top_sector_stocks as $s => $v)
var {{ $sector_trim[$s] }}ctx = document.getElementById('{{ $sector_trim[$s] }}Canvas').getContext('2d');
var chart = new Chart({{ $sector_trim[$s] }}ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [
            @foreach($v as $st)
                '{!! trim($stocks[$st->symbol]['symbol']) !!}',
            @endforeach
            ],
        datasets: [
        {
            label: 'PE',
            backgroundColor: "orange",
            minBarLength: '100',
            data: [
            @foreach($v as $st)
                '{{ round($stocks[$st->symbol]['pe_ratio']) }}',
            @endforeach
            ]
        },
        {
            label: 'PBV',
            backgroundColor: "indigo",
            data: [
            @foreach($v as $st)
                '{{ round($stocks[$st->symbol]['pbv_ratio']) }}',
            @endforeach
            ]
        },
        {
            label: 'PE*PBV',
            backgroundColor: "lime",
            data: [
            @foreach($v as $st)
                '{{ pow(500/round(sqrt($stocks[$st->symbol]['pbv_ratio']*$stocks[$st->symbol]['pe_ratio']) + 1),1.3) }}',
            @endforeach
            ]
        }
        ]
    },

    // Configuration options go here
    options: {}
});

@endforeach
</script>

</body>
</html>