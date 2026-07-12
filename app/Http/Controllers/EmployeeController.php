<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $userId = session('user_id');

        // Mood Dominan Hari Ini
        $moodToday = MoodLog::where('anonymous_user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->select('mood', DB::raw('count(*) as total'))
            ->groupBy('mood')
            ->orderBy('total', 'desc')
            ->first()->mood ?? 'Belum ada';

        // Mood Dominan Minggu Ini
        $moodWeek = MoodLog::where('anonymous_user_id', $userId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->select('mood', DB::raw('count(*) as total'))
            ->groupBy('mood')
            ->orderBy('total', 'desc')
            ->first()->mood ?? 'Belum ada';

        // Mood Dominan Bulan Ini
        $moodMonth = MoodLog::where('anonymous_user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select('mood', DB::raw('count(*) as total'))
            ->groupBy('mood')
            ->orderBy('total', 'desc')
            ->first()->mood ?? 'Belum ada';

        // Data Tren Mood (7 Hari Terakhir)
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $moodScoreMap = [
            'Bahagia' => 5,
            'Senang' => 4,
            'Netral' => 3,
            'Cemas' => 2,
            'Sedih' => 1,
            'Stress' => 0,
            'Marah' => 0,
        ];

        $trendData = [];
        $trendLabels = [];

        foreach ($dates as $date) {
            $trendLabels[] = Carbon::parse($date)->format('d M');
            $dailyMood = MoodLog::where('anonymous_user_id', $userId)
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $score = $dailyMood ? ($moodScoreMap[$dailyMood->mood] ?? 3) : null;
            $trendData[] = $score;
        }

        return view('employee.dashboard', compact('moodToday', 'moodWeek', 'moodMonth', 'trendLabels', 'trendData'));
    }

    public function mood()
    {
        return view('employee.mood');
    }

    public function saveMood(Request $request)
    {
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
        $riwayat = MoodLog::where('anonymous_user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.riwayat-mood', compact('riwayat'));
    }
}