@extends('layouts.app')

@section('content')

<div class="container">
    <div class="bg-light p-3 border rounded">
        <h1 class="h4">Add Stock</h1>
        <form action="/portfolio" method="post" class="form">
        @csrf
        <div class="form-row">
            <div class="col-3">
            <select name="symbol" id="" class="form-control" required>
                <option disabled selected value> -- select an option -- </option>
                @foreach( $stocks as $st )
                    <option value="{{ $st->symbol }}">{{ $st->symbol }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-2">
            <input type="number" class="form-control" name="price" placeholder="Buy Price" required>
            </div>
            <div class="col-2">
            <input type="number" class="form-control" name="rally" placeholder="Rally Low" required>
            </div>
            <button type="submit" class="btn btn-primary col-1">Add</button>
        </div>
        </form>
    </div>

    <h2 class="h4 mt-4 mb-3">Portfolio</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <th>Stock</th>
            <th>Buy Price</th>
            <th>LTP</th>
            <th>5%</th>
            <th>10%</th>
            <th>15%</th>
            <th>25%</th>
            <th>40%</th>
            <th>65%</th>
            <th></th>
        </thead>
        <tbody>
            @foreach( $portfolio as $stock)
            <tr>
                <td>{{ $stock->symbol }}</td>
                <td>{{ $stock->buy_price }}</td>
                <td>{{ $stock->ltp }}</td>
                <td class="@if($stock->bg_class == 's_5') {{ $stock->bg_color }} @endif">{{ $stock->s_5 }}</td>
                <td class="@if($stock->bg_class == 's_10') {{ $stock->bg_color }} @endif">{{ $stock->s_10 }}</td>
                <td class="@if($stock->bg_class == 's_15') {{ $stock->bg_color }} @endif">{{ $stock->s_15 }}</td>
                <td class="@if($stock->bg_class == 's_25') {{ $stock->bg_color }} @endif">{{ $stock->s_25 }}</td>
                <td class="@if($stock->bg_class == 's_40') {{ $stock->bg_color }} @endif">{{ $stock->s_40 }}</td>
                <td class="@if($stock->bg_class == 's_65') {{ $stock->bg_color }} @endif">{{ $stock->s_65 }}</td>
                <td>
                    <form action="./portfolio/{{ $stock->id }}/edit" method="get" class="d-inline">
                        <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                    </form>
                    <form action="./portfolio/{{ $stock->id }}" method="post" class="d-inline">
                    @csrf
                        <input type="hidden" name="_method" value="delete">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection