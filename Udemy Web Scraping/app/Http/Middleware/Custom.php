<?php

namespace App\Http\Middleware;

use Closure;
use App\LfMeta;
use App\LfCourse;

class Custom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user_ip = $_SERVER['REMOTE_ADDR'];
        if($user_ip != '127.0.0.1') {
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $_SERVER['user_ip'] = $user_ip;
            $_SERVER['user_country'] = $geo["geoplugin_countryName"];
            $_SERVER['user_city'] = $geo["geoplugin_city"];
        }
        else {
            $_SERVER['user_country'] = "Unknown";
            $_SERVER['user_city'] = "Unknown"; 
        }

        return $next($request);
    }
}
