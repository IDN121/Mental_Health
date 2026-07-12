<?php

namespace App\Http\Controllers;

use App\Models\AnonymousUser;
use App\Models\Message;
use App\Models\MoodLog;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class CounselorController extends Controller
{
    public function adminDashboard()
    {
        $employeeCount = AnonymousUser::count();
        $chatCount = Message::count(); // Total keseluruhan pesan (hanya angka)
        $moodCount = MoodLog::whereDate('created_at', today())->count();

        // ⚠ Peringatan Risiko Tinggi
        $highRiskCount = ChatSession::whereDate('session_date', today())
            ->whereIn('risk_level', ['HIGH', 'CRITICAL'])
            ->count();

        // Admin hanya boleh melihat sesi, bukan pesan mentah
        $recentSessions = ChatSession::with('anonymousUser')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'employeeCount',
            'chatCount',
            'moodCount',
            'highRiskCount',
            'recentSessions'
        ));
    }

    public function karyawanDashboard()
    {
        // Untuk Karyawan, tampilkan pesan yang baru atau diproses
        $activeChats = Message::whereIn('status', ['baru', 'diproses'])
            ->with('anonymousUser')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $chatCount = Message::where('status', 'baru')->count();

        return view('karyawan.dashboard', compact('activeChats', 'chatCount'));
    }

    public function monitoring(Request $request)
    {
        // Admin TIDAK BOLEH membaca pesan mentah. Gunakan ChatSession.
        $query = ChatSession::with('anonymousUser');

        if ($request->has('risk') && $request->risk != '') {
            $query->where('risk_level', $request->risk);
        }

        $sessions = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('counselor.monitoring', compact('sessions'));
    }

    public function statistik()
    {
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
        $sessions = ChatSession::with('anonymousUser')
            ->orderBy('session_date', 'desc')
            ->paginate(20);

        return view('counselor.laporan', compact('sessions'));
    }

    public function exportLaporan()
    {
        $sessions = ChatSession::with('anonymousUser')
            ->orderBy('session_date', 'desc')
            ->get();

        $filename = "laporan_sesi_ai_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($handle, "\xEF\xBB\xBF");
        
        // Headers (NO RAW MESSAGES ALLOWED)
        fputcsv($handle, ['Tanggal', 'Kode Karyawan', 'Jumlah Pesan', 'Mood Dominan', 'Risk Level', 'Summary']);

        foreach ($sessions as $ses) {
            fputcsv($handle, [
                $ses->session_date,
                $ses->anonymousUser->unique_code ?? 'N/A',
                $ses->message_count,
                strtoupper($ses->dominant_mood ?? '-'),
                $ses->risk_level,
                $ses->summary
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
        $sessions = ChatSession::with('anonymousUser')
            ->orderBy('session_date', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('counselor.pdf_laporan', compact('sessions'));
        return $pdf->download('laporan_sesi_ai_' . date('Ymd_His') . '.pdf');
    }
}