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
            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau Password salah');
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