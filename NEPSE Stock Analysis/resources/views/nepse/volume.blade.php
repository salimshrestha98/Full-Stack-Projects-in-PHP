@extends('layouts.app')

@section('content')

<div class="my-5 py-5"></div>

<table class="table table-striped border" id="tbl">
    <thead>
        <th>S.N</th>
        <th>Symbol</th>
        <th>Trade Volume</th>
        <th>30 Days Avg Volume</th>
        <th>30 Day Ratio</th>
        <th>Prev Volume</th>
        <th>Prev Vol Ratio</th>
        <th>Change</th>
        <th>Index</th>
    </thead>

    <tbody>
        @foreach( $stocks as $stock)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $stock->symbol }}</td>
                <td>{{ $stock->trade_vol }}</td>
                <td>{{ $stock['30_day_avg_volume'] }}</td>
                <td>{{ $stock['30_vol_ratio'] }}</td>
                <td>{{ $stock->prev_vol }}</td>
                <td>{{ $stock->prev_vol_ratio }}</td>
                <td>{{ $stock->change }}</td>
                <td>{{ $stock->index }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>
$('#tbl').DataTable({
    paging: false,
    fixedHeader: true,
    order: [[8, 'desc']]
});
$(this).DataTable();
</script>

@endsection