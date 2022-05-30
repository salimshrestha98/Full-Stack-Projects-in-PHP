<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoostPage extends Model
{
    protected $fillable = [
        'url',
        'clicks',
        'phrases',
        'serp_img',
        'location'
    ];
}
