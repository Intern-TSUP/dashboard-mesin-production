<?php

namespace App\Http\Middleware;

use Closure;
use Http;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkTesting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiUrl = 'https://api-pharma.kalbe.co.id';

        try {
            // Mengirim request ke API
            $response = Http::get($apiUrl);

            // Jika berhasil, kembalikan data
            if ($response->status() == 200) {
                return $next($request);
            }
            return $next($request);
        } catch (\Exception $e) {
            // Tangani jika terjadi error jaringan
            if (strpos($e->getMessage(), 'cURL error 6') !== false) {
                return response()->view('layout.network');
            }

            return response()->view('layout.network');
        }
    }
}
