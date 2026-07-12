<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Counselor;
use App\Models\AnonymousUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // =========================
    // LOGIN ADMIN
    // =========================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $admin = Counselor::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            if ($admin->role === 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/karyawan/dashboard');
            }
        }

        return back()->with('error', 'Username atau Password salah');
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    // =========================
    // LOGIN KARYAWAN
    // =========================
    public function employeeForm()
    {
        return view('auth.employee');
    }

    public function employeeLogin(Request $request)
    {
        $request->validate([
            'unique_code' => 'required|digits:4',
        ], [
            'unique_code.required' => 'Kode unik wajib diisi',
            'unique_code.digits' => 'Kode unik harus berupa 4 angka',
        ]);

        // ambil user berdasarkan kode unik
        $user = AnonymousUser::where('unique_code', $request->unique_code)->first();

        // kalau belum ada, buat baru
        if (!$user) {
            $user = AnonymousUser::create([
                'unique_code' => $request->unique_code,
            ]);
        }

        // simpan session
        Session::put('user_id', $user->id);

        return redirect('/employee/dashboard');
    }
}