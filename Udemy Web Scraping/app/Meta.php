<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $fillable = [
        'meta',
        'name',
        'value',
        'value_2'
    ];
}
