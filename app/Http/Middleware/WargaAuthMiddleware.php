<?php

namespace App\Http\Middleware;

use Closure;
use App\Sesi;
use Validator;

class WargaAuthMiddleware
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
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'token' => 'string|required|exists:sesi,token',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Silakan masuk terlebih dahulu.'
            ], 401);
        }

        $sesi = Sesi::where('token', $inputs['token'])->first();

        if ($sesi->waktu_keluar !== null) {
            return response()->json([
                'message' => 'Sesi Anda telah berakhir. Silakan masuk kembali.'
            ], 401);
        }

        return $next($request);
    }
}
