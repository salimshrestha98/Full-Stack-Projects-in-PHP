<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nepse extends Model
{
    protected $fillable = [
        'symbol',
        'date',
        'day',
        'open',
        'high',
        'low',
        'close',
        'change',
        'qty',
        'code',
        'index_1',
        'index_2'
    ];
}
