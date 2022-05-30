<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'type',
        'site',
        'product',
        'categories',
        'target',
        'location',
        'width',
        'height',
        'content',
        'remarks'
    ];
}
