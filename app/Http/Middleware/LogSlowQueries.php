<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSlowQueries
{
    /**
     * Handle an incoming request Query.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\DB::enableQueryLog();

        $response = $next($request);

        $querys = \Illuminate\Support\Facades\DB::getQueryLog();


        foreach($querys as $query){
            $executionTime = $query['time'];

            $slowQueryThreshold = 100; // 100ms

            if ($executionTime > $slowQueryThreshold) {

                $controller = $request->route()->getAction('controller');

                Log::warning("Slow Query in Controller: $controller", [
                    'time' => $executionTime,
                    'query' => $query['query'],
                    'bindings' => $query['bindings'],
                ]);
            }
            
        }

        return $response;
    }
}
