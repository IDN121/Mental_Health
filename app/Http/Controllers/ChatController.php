<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        $messages = Message::where('anonymous_user_id', session('user_id'))
            ->orderBy('created_at', 'asc')
            ->get();

        return view('employee.chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $userId = session('user_id');

        // Prepare context by fetching the last 5 messages
        $history = Message::where('anonymous_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->reverse()
            ->map(function ($msg) {
                $role = $msg->sender == 'employee' ? 'user' : 'model';
                return [
                    'role' => $role,
                    'parts' => [['text' => $msg->message]]
                ];
            })->values()->toArray();

        // 1. Simpan pesan user (sementara emotion null)
        $userMessage = Message::create([
            'anonymous_user_id' => $userId,
            'sender' => 'employee',
            'message' => $request->message,
            'is_read' => 0
        ]);

        // 2. Call Gemini API
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            Log::error('GEMINI_API_KEY not found in .env');
            // Mock response if API key is not set
            $botReply = "Maaf, sistem konseling sedang tidak dapat dihubungi. Silakan coba lagi nanti.";
            $emotion = 'netral';
        } else {
            $systemPrompt = "Kamu adalah AI konselor kesehatan mental yang empatik, ramah, dan tidak menghakimi. Tugasmu adalah memberikan tanggapan yang menenangkan atas curhatan user. Jangan hanya memberi kuesioner. Berikan saran sederhana dan langkah kecil yang aman. JANGAN berikan diagnosis medis/psikologis. Jika user menyebut ingin menyakiti diri sendiri atau bunuh diri, sarankan mereka untuk menghubungi orang terdekat atau layanan darurat. Selain itu, analisislah emosi user dari pesannya (pilih SATU dari: sedih, marah, cemas, takut, kecewa, senang, netral, stres). Kembalikan respons dalam format JSON dengan key 'reply' (string) dan 'emotion' (string huruf kecil).";

            $promptContent = [
                ['role' => 'user', 'parts' => [['text' => $systemPrompt]]],
                ['role' => 'model', 'parts' => [['text' => 'Mengerti. Saya akan merespons dalam format JSON sesuai instruksi.']]],
            ];
            
            $contents = array_merge($promptContent, $history);
            $contents[] = ['role' => 'user', 'parts' => [['text' => $request->message]]];

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                    'contents' => $contents,
                    'generationConfig' => [
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                $result = $response->json();
                
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $jsonText = $result['candidates'][0]['content']['parts'][0]['text'];
                    $data = json_decode($jsonText, true);
                    $botReply = $data['reply'] ?? "Maaf, aku tidak mengerti maksudmu.";
                    $emotion = strtolower($data['emotion'] ?? 'netral');
                } else {
                    $botReply = "Maaf, ada gangguan pada sistem.";
                    $emotion = 'netral';
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Error: ' . $e->getMessage());
                $botReply = "Maaf, sistem sedang sibuk.";
                $emotion = 'netral';
            }
        }

        // 3. Update user message dengan emotion
        $userMessage->update([
            'emotion' => $emotion,
            'confidence' => 95.0
        ]);

        // 4. Simpan balasan bot
        Message::create([
            'anonymous_user_id' => $userId,
            'sender' => 'counselor',
            'message' => $botReply,
            'is_admin' => 0,
            'is_read' => 0
        ]);

        // 5. Otomatis catat ke riwayat mood jika emotion relevan
        $todayMood = \App\Models\MoodLog::where('anonymous_user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if (!$todayMood) {
            \App\Models\MoodLog::create([
                'anonymous_user_id' => $userId,
                'emotion_label' => ucfirst($emotion),
                'confidence_score' => 95.0,
                'notes' => 'Terdeteksi dari curhatan: "' . \Illuminate\Support\Str::limit($request->message, 50) . '"'
            ]);
        } else {
             $todayMood->update([
                 'emotion_label' => ucfirst($emotion),
                 'confidence_score' => 95.0,
             ]);
        }

        return redirect('/chat');
    }

    public function messages($id)
    {
        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}