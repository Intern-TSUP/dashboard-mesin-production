<?php

namespace App\Http\Middleware;

use App\Models\Permissions;
use App\Models\PermissionsLine; 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJobLvlPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        // Dapatkan pengguna yang sedang login
        $user = $request->user();

        // Cek apakah pengguna sudah login
        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login untuk melanjutkan.');
        }
        // Dapatkan nama rute saat ini
        $currentRoute = $request->route()->getName();

        // Cek apakah pengguna memiliki line_id di profilnya
        if ($user->profile?->line_id) {
            $lineId = $user->profile->line_id;
            
            // Cek di tabel permission_lines (atau tabel sejenis)
            $hasPermission = PermissionsLine::where('line_id', $lineId)
                                            ->where('url', $currentRoute)
                                            ->exists();

        } else {
            // Cek apakah pengguna memiliki role
            if ($user->jobLvl) {
                
                // Cek apakah permission untuk jobLvl dan URL saat ini ada di database
                $hasPermission = Permissions::where('role_id', $user->roles->id)
                                            ->where('url', $currentRoute)
                                            ->exists();
            }
        }

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