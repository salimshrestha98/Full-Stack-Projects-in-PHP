<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LfMeta;

class MetasController extends Controller
{
    public function add($name,$value) {
        $meta = new LfMeta();
        $meta->name = $name;
        $meta->value = $value;
        $meta->save();
    }

    public function test() {
        echo phpinfo();
    }
}
