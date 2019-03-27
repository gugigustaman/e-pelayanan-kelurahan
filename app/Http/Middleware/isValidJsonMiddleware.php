<?php

namespace App\Http\Middleware;

use Closure;

class isValidJsonMiddleware
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
        if (!$request->isJson()) {
            return response()->json([
                'message' => 'Permintaan tidak valid.'
            ], 400);
        }

        if(empty($request->json()->all())) {
            return response()->json([
                'message' => 'Permintaan tidak valid.'
            ], 400);
        }
        return $next($request);
    }
}
