<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Counselor;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Pengecekan untuk role Admin dan Karyawan
        if (in_array($role, ['admin', 'karyawan'])) {
            if (!Session::has('admin_id')) {
                return redirect('/login');
            }

            $user = Counselor::find(Session::get('admin_id'));
            
            // Jika user tidak ditemukan (mungkin karena DB direset), bersihkan session
            if (!$user) {
                Session::forget('admin_id');
                return redirect('/login')->with('error', 'Sesi telah berakhir karena reset sistem. Silakan login kembali.');
            }

            if ($user->role !== $role) {
                // Redirect ke dashboard sesuai role jika ada, atau 403
                if ($user->role == 'admin') {
                    return redirect('/admin/dashboard');
                } else if ($user->role == 'karyawan') {
                    return redirect('/karyawan/dashboard');
                }
                return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }
        
        // Pengecekan untuk role Client/User (employee)
        if ($role === 'client') {
            if (!Session::has('user_id')) {
                return redirect('/employee');
            }

            // Jika user tidak ditemukan di DB (karena reset), bersihkan session
            $client = \App\Models\AnonymousUser::find(Session::get('user_id'));
            if (!$client) {
                Session::forget('user_id');
                return redirect('/employee')->with('error', 'Sesi telah berakhir karena reset sistem. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}
