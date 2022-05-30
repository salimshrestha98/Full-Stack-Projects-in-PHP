<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LfCourse extends Model
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
        'name',
        'filename',
        'filesize',
        'file_url',
        'torrent_url',
        'cat_1',
        'cat_2',
        'cat_3'
    ];

}
