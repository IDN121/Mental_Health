<?php

namespace App\Http\Controllers;

use App\Models\AnonymousUser;
use App\Models\Message;
use App\Models\MoodLog;
use Illuminate\Http\Request;

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

        $latestMessages = Message::latest()->take(5)->get();

        return view('counselor.dashboard', compact(
            'employeeCount',
            'chatCount',
            'moodCount',
            'latestMessages'
        ));
    }

    public function monitoring(Request $request)
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $query = Message::with('anonymousUser')->where('sender', 'employee');

        if ($request->has('emotion') && $request->emotion != '') {
            $query->where('emotion', $request->emotion);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('counselor.monitoring', compact('messages'));
    }

    public function statistik()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        // Get mood data grouped by mood type for Doughnut chart
        $moodDistribution = MoodLog::selectRaw('mood, COUNT(*) as count')
            ->groupBy('mood')
            ->pluck('count', 'mood')
            ->toArray();

        // Default moods array
        $defaultMoods = ['Senang' => 0, 'Sedih' => 0, 'Marah' => 0, 'Cemas' => 0, 'Netral' => 0];
        $moodDistribution = array_merge($defaultMoods, $moodDistribution);
        
        $totalMoods = array_sum($moodDistribution);

        // Calculate percentages
        $moodPercentages = [];
        foreach ($moodDistribution as $mood => $count) {
            $moodPercentages[$mood] = $totalMoods > 0 ? round(($count / $totalMoods) * 100, 1) : 0;
        }

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

        return view('counselor.statistik', compact('moodDistribution', 'moodPercentages', 'totalMoods', 'dates', 'countsByDate'));
    }

    public function laporan()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $messages = Message::with('anonymousUser')
            ->where('sender', 'employee')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('counselor.laporan', compact('messages'));
    }

    public function exportLaporan()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $messages = Message::with('anonymousUser')
            ->where('sender', 'employee')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "laporan_monitoring_ai_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($handle, "\xEF\xBB\xBF");
        
        // Headers
        fputcsv($handle, ['Tanggal', 'Kode Karyawan', 'Pesan', 'Emosi (AI)', 'Confidence (%)']);

        foreach ($messages as $msg) {
            fputcsv($handle, [
                $msg->created_at->format('Y-m-d H:i:s'),
                $msg->anonymousUser->unique_code ?? 'N/A',
                $msg->message,
                strtoupper($msg->emotion),
                $msg->confidence
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportPdfLaporan()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $messages = Message::with('anonymousUser')
            ->where('sender', 'employee')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('counselor.pdf_laporan', compact('messages'));
        return $pdf->download('laporan_monitoring_ai_' . date('Ymd_His') . '.pdf');
    }
}