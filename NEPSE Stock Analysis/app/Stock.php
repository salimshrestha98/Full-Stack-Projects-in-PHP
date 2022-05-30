<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'date',
        'symbol',
        'name',
        'sector',
        'ltp',
        '52_high',
        '52_low',
        '120_avg',
        'yield',
        'eps',
        'pe_ratio',
        'book_value',
        'pbv_ratio',
        'dividend',
        'bonus',
        'return',
        'listed_shares',
        'market_cap',
        '30_day_avg_volume',
        'index_1',
        'index_2'
    ];
}
