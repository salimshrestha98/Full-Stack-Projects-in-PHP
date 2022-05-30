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
    <style>
        .table th, .table td {
            font-size: 12px;
            padding: 0.2rem 0.75rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <a class="navbar-brand mr-5" href="#">Stocka</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mx-3">
        <form action="/" method="post">
            @csrf
            <input type="hidden" name="p" value="@samalamadumalama..">
            <input type="submit" value="Home">
        </form>
      </li>
      <li class="nav-item mx-3">
        <a class="nav-link" href="#sectors">Sectors</a>
      </li>
      <li class="nav-item mx-3">
        <a href="#ext" class="nav-link" id="sites-link">Sites</a>
      </li>
      <li class="pt-2 ml-5 mr-3">
        Last Updated: {{ $last_date }}
      </li>
      <li>
        <form action="." method="post">
        @csrf
            <input type="hidden" name="p" value="@samalamadumalama..">
            <input type="hidden" name="action" value="update">
            <input type="submit" value="Update" class="btn btn-sm btn-primary mx-3">
        </form>
      </li>
      <li>
        <form action="." method="post">
        @csrf
            <input type="hidden" name="p" value="@samalamadumalama..">
            <input type="hidden" name="action" value="reset">
            <input type="submit" value="Reset" class="btn btn-sm btn-warning mx-3">
        </form>
      </li>

      

      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
    <!-- <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
  </div>
</nav>
</div>

    <div class="my-5 py-1"></div>

    <table class="table table-striped table-bordered mb-5" style="width:100%" id="f-list">
        <thead>
            <th>Sn.</th>
            <th>Symbol</th>
            <th>Name</th>
            <th>Sector</th>
            <th>LTP</th>
            <th>Sec. Avg.</th>
            <th>LTP - SA</th>
            <th>52 High</th>
            <th>52H - LTP</th>
            <th>52 Low</th>
            <th>Yield</th>
            <th>PE Ratio</th>
            <th>PBV Ratio</th>
            <th>Index</th>
            <th>Index 2</th>
            <th>Graham Price</th>
            <th>GP - LTP</th>
        </thead>
        <tbody>
        @foreach($latest_stocks as $st)
            <tr class="@if($st->index_1 < 1) bg-danger @endif">
                <td><a href="https://www.sharesansar.com/company/{{ $st->symbol }}" target="_blank" title="Sharesansar">{{ $loop->index+1 }}</a></td>
                <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $st->symbol }}" target="_blank" title="Merolagani">{{ $st->symbol }}</a></td>
                <td><a href="https://bizmandu.com/content/company/{{ $st->symbol }}" target="_blank" title="Bizmandu">{{ $st->name }}</a></td>
                <td><a href="#{{ str_replace(' ','-',trim($st->sector)) }}" title="Goto">{{ $st->sector }}</a></td>
                <td>{{ $st->ltp }}</td>
                <td title="Sector Avg">{{ $sector_avg[$st->sector]['ltp'] }}</td>
                <td class="
                @if($st['ltp-sa'] < 5) bg-success 
                @elseif($st['ltp-sa'] < 25) bg-warning
                @else bg-danger
                @endif
                
                ">{{ $st['ltp-sa'] }}%</td>
                <td title="52 High">{{ $st['52_high'] }}</td>
                <td>{{ round(($st['52_high']-$st->ltp)/$st->ltp,2)*100 }}%</td>
                <td title="52 Low">{{ $st['52_low'] }}</td>
                <td class="@if($st['yield'] > $sector_avg[$st->sector]['yield'] || $st['yield'] > 100) bg-success @endif" title="1 Year Yield">{{ $st['yield'] }}</td>
                
                <td class="
                @if($st['pe_ratio'] < $sector_avg[$st->sector]['pe_ratio'] && $st['pe_ratio'] > 0  && $st['pe_ratio'] < 40) bg-success 
                @elseif( $st['pe_ratio'] > 40 || $st['pe_ratio'] < 0) bg-danger
                @else bg-warning
                @endif
                " title="PE Ratio">{{ $st['pe_ratio'] }}</td>
                
                <td class="
                @if( $st['pbv_ratio'] > 0 && $st['pbv_ratio'] < 3) bg-success 
                @elseif( $st['pbv_ratio'] > 5 && $st['pbv_ratio'] < $sector_avg[$st->sector]['pbv_ratio']) bg-primary
                @elseif( $st['pbv_ratio'] < 0 ||  $st['pbv_ratio'] > 5) bg-danger
                @else bg-warning
                @endif
                " title="PBV Ratio">{{ $st['pbv_ratio'] }}</td>
                <td title="Index 1">{{ $st->index_1 }}</td>
                <td title="Index 2">{{ $st->index_2 }}</td>
                <td title="Graham Price">{{ $st->graham_price }}</td>
                <td title="GP - LTP" class="@if($st->ltp <= $st->graham_price) bg-success @elseif($st->ltp >= $st->graham_price*1.2) bg-danger text-white @else bg-warning @endif">{{ round(($st->ltp/$st->graham_price)*100) - 100 }}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="sectors"></div>
        @foreach($top_sector_stocks as $s => $v)
        @if($v->count())
        <br>
        <br id="{{ str_replace(' ','-',trim($s)) }}">
        <br>
        <strong class="my-3">Top {{ ucwords($s) }} Stocks</strong>
            <table class="table table-striped table-bordered mt-2 mb-5" style="width:100%">
                <thead>
                    <th>Sn.</th>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Sector</th>
                    <th>LTP</th>
                    <th>LTP - SA</th>
                    <th>52 High</th>
                    <th>52H - LTP</th>
                    <th>52 Low</th>
                    <th>Yield</th>
                    <th>EPS</th>
                    <th>PE Ratio</th>
                    <th>Book Value</th>
                    <th>PBV Ratio</th>
                    <th>Index</th>
                    <th>Index 2</th>
                </thead>
                <tbody>
                <tr class="bg-warning">
                    <td>0</td>
                    <td></td>
                    <td>Sector Averages</td>
                    <td></td>
                    <td>{{ $sector_avg[$s]['ltp'] }}</td>
                    <td></td>
                    <td>{{ $sector_avg[$s]['52_high'] }}</td>
                    <td></td>
                    <td>{{ $sector_avg[$s]['52_low'] }}</td>
                    <td>{{ $sector_avg[$s]['yield'] }}</td>
                    <td>{{ $sector_avg[$s]['eps'] }}</td>
                    <td>{{ $sector_avg[$s]['pe_ratio'] }}</td>
                    <td>{{ $sector_avg[$s]['book_value'] }}</td>
                    <td>{{ $sector_avg[$s]['pbv_ratio'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($v as $st)
                        <tr>
                            <td><a href="https://www.sharesansar.com/company/{{ $st->symbol }}" target="_blank">{{ $loop->index+1 }}</a></td>
                            <td><a href="https://merolagani.com/CompanyDetail.aspx?symbol={{ $st->symbol }}" target="_blank">{{ $st->symbol }}</a></td>
                            <td>{{ $st->name }}</td>
                            <td>{{ $st->sector }}</td>
                            <td class="@if($st->ltp-25 < $sector_avg[$st->sector]['ltp']) bg-success @endif">{{ $st->ltp }}</td>
                            <td>{{ round(($st->ltp - $sector_avg[$st->sector]['ltp'])/$sector_avg[$st->sector]['ltp'],2)*100 }}%</td>
                            <td>{{ $st['52_high'] }}</td>
                            <td>{{ round(($st['52_high']-$st->ltp)/$st->ltp,2)*100 }}%</td>
                            <td>{{ $st['52_low'] }}</td>
                            <td class="@if($st['yield'] > $sector_avg[$st->sector]['yield']) bg-success @endif">{{ $st['yield'] }}</td>
                            <td>{{ $st['eps'] }}</td>
                            <td class="@if($st['pe_ratio'] < $sector_avg[$st->sector]['pe_ratio']) bg-success @endif">{{ $st['pe_ratio'] }}</td>
                            <td>{{ $st['book_value'] }}</td>
                            <td class="@if($st['pbv_ratio'] < $sector_avg[$st->sector]['pbv_ratio']) bg-success @endif">{{ $st['pbv_ratio'] }}</td>
                            <td>{{ $st['index_1'] }}</td>
                            <td>{{ $st['index_2'] }}</td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @endforeach
    </div>
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
</body>
</html>