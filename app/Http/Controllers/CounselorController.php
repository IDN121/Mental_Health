<?php

namespace App\Http\Controllers;

use App\Models\AnonymousUser;
use App\Models\Message;
use App\Models\MoodLog;

class CounselorController extends Controller
{
    public function dashboard()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $employeeCount = AnonymousUser::count();

        $chatCount = Message::count();

        $moodCount = MoodLog::whereDate('created_at', today())->count();

        $latestMessages = Message::latest()
            ->take(5)
            ->get();

        return view('counselor.dashboard', compact(
            'employeeCount',
            'chatCount',
            'moodCount',
            'latestMessages'
        ));
    }

    public function monitoring()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        // Get mood data grouped by mood type for Doughnut chart
        $moodDistribution = MoodLog::selectRaw('mood, COUNT(*) as count')
            ->groupBy('mood')
            ->pluck('count', 'mood')
            ->toArray();

        // Default moods array to ensure all keys exist for Chart.js
        $defaultMoods = ['Senang' => 0, 'Sedih' => 0, 'Marah' => 0, 'Cemas' => 0, 'Netral' => 0];
        $moodDistribution = array_merge($defaultMoods, $moodDistribution);

        // Get mood data grouped by date for Line chart (last 7 days)
        $recentMoods = MoodLog::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });

        $dates = [];
        $countsByDate = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $countsByDate[] = $recentMoods->has($date) ? $recentMoods[$date]->count() : 0;
        }

        return view('counselor.monitoring', compact('moodDistribution', 'dates', 'countsByDate'));
    }
}