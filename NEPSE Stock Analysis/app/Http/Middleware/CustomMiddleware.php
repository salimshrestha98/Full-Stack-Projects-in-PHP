<?php

namespace App\Http\Middleware;

use Closure;

class CustomMiddleware
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
        $_SESSION['now'] = date('Y-m-d H:i:s',strtotime("+345 minutes"));
        $_SESSION['today'] = date('Y-m-d',strtotime("+345 minutes"));
        return $next($request);
    }
}
