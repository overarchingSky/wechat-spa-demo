<?php
/**
 * Created by IntelliJ IDEA.
 * User: xiahua
 * Date: 2018/3/15
 * Time: 下午10:42
 */

namespace App\Http\Middleware;


class EnableCrossDomain
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        return $response;
    }
}