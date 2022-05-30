<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'url',
        'title',
        'sub_title',
        'authors',
        'wyl',
        'requirements',
        'description',
        'wtcif',
        'img_url',
        'filename',
        'filesize',
        'cat_1',
        'cat_2',
        'cat_3'
    ];
}
