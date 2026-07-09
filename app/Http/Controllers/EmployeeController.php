<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodLog;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        return view('employee.dashboard');
    }

    public function mood()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        return view('employee.mood');
    }

    public function saveMood(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        $request->validate([
            'mood' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        MoodLog::create([
            'anonymous_user_id' => session('user_id'),
            'mood' => $request->mood,
            'notes' => $request->notes,
        ]);

        return redirect('/employee/riwayat-mood')->with('success', 'Mood berhasil disimpan!');
    }

    public function riwayatMood()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        $riwayat = MoodLog::where('anonymous_user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.riwayat-mood', compact('riwayat'));
    }
}