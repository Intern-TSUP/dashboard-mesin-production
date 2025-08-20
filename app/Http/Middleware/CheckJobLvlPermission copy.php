<?php

namespace App\Http\Middleware;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\UserOutstanding;
use App\Models\UserRevisi;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJobLvlPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Dapatkan pengguna yang sedang login
        $user = $request->user();

        // Cek apakah pengguna sudah login dan memiliki jobLvl
        if (!$user || !$user->jobLvl) {
            return redirect('/login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        // Dapatkan nama rute saat ini
        $currentRoute = $request->route()->getName();
        // dd($currentRoute);

        // Cek apakah permission untuk jobLvl dan URL saat ini ada di database
        $hasPermission = Permissions::where('role_id', $user->roles->id)
            ->where('url', $currentRoute)
            ->exists();


        // Jika tidak ada izin, tampilkan halaman forbidden
        if ($hasPermission) {
            return $next($request);
        }

        // Forbidden fallback
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'Anda Tidak Memiliki Akses Pada Action ini'])
            : response()->view('layout.forbidden');

    }
}
