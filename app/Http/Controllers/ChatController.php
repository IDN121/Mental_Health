<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AnonymousUser;
use App\Models\MoodLog;
use App\Models\ChatSession;

class ChatController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        // Ambil dan bersihkan data korup di database sekalian agar database kembali bersih!
        $messages = Message::where('anonymous_user_id', session('user_id'))
            ->orderBy('created_at', 'asc')
            ->get();
            
        foreach ($messages as $msg) {
            $cleaned = $this->getReplyText($msg->message);
            if ($cleaned !== $msg->message) {
                $msg->update(['message' => $cleaned]);
            }
        }

        return view('employee.chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => [
                'required',
                'string',
                'min:1',
                'max:3000',
            ],
        ]);

        $userId = session('user_id');
        $userExists = AnonymousUser::find($userId);
        
        if (!$userExists) {
            session()->forget('user_id');
            return redirect('/employee')->with('error', 'Sesi kedaluwarsa karena reset database. Silakan login ulang.');
        }

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-3.5-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        if (empty($apiKey)) {
            Log::error('Gemini API Error: API Key not configured.', [
                'user_id' => $userId,
                'time' => now()->toDateTimeString()
            ]);
            return $this->saveOfflineResponse($userId, $request->message, "Maaf, saya sedang mengalami kendala teknis. Silakan coba beberapa saat lagi.");
        }

        $systemPrompt = "Kamu adalah asisten pendamping kesehatan mental untuk karyawan.\n\nTugas utama:\n- Menanggapi cerita pengguna dengan hangat, empatik, dan tidak menghakimi.\n- Memvalidasi perasaan pengguna tanpa berlebihan.\n- Membantu pengguna memahami emosi dan situasinya.\n- Memberikan langkah kecil yang aman, realistis, dan dapat dilakukan.\n- Tidak memberikan diagnosis gangguan mental atau memberikan obat.\n- Harus membalas dalam bahasa Indonesia yang natural.\n- PENTING: Jangan meniru riwayat pesan yang terpotong jika ada di riwayat percakapan. Selalu selesaikan kalimatmu sampai selesai dan akhiri dengan tanda baca yang benar (titik, tanda tanya, atau tanda seru).\n\nSelain membalas pesan, kamu juga diwajibkan untuk:\n1. Meringkas percakapan keseluruhan secara singkat (summary).\n2. Mendeteksi emosi saat ini (emotion).\n3. Mengevaluasi tingkat risiko pengguna (risk_level): LOW (stress ringan), MEDIUM (tekanan kerja tinggi, sedih berkepanjangan), HIGH (putus asa, hilang motivasi berat), CRITICAL (bunuh diri, melukai diri/orang lain).\n\nKembalikan jawaban WAJIB sesuai JSON Schema yang diberikan.";

        // History Limit 20
        $history = Message::where('anonymous_user_id', $userId)
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->reverse()
            ->map(function ($msg) {
                return [
                    'role' => $msg->sender === 'employee' ? 'user' : 'model',
                    'parts' => [['text' => $this->getReplyText($msg->message)]]
                ];
            })
            ->values()
            ->toArray();

        $contents = $history;
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $request->string('message')->trim()->toString()]]
        ];

        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'topP' => 0.9,
                'maxOutputTokens' => 1000,
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'reply' => [
                            'type' => 'STRING',
                            'description' => 'Balasan konselor AI untuk pengguna',
                        ],
                        'emotion' => [
                            'type' => 'STRING',
                            'enum' => ['Sedih', 'Marah', 'Cemas', 'Stress', 'Bahagia', 'Netral'],
                        ],
                        'confidence' => [
                            'type' => 'INTEGER',
                        ],
                        'risk_level' => [
                            'type' => 'STRING',
                            'enum' => ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'],
                        ],
                        'summary' => [
                            'type' => 'STRING',
                            'description' => 'Ringkasan singkat maksimal 2 kalimat mengenai masalah yang dialami pengguna sejauh ini',
                        ]
                    ],
                    'required' => ['reply', 'emotion', 'confidence', 'risk_level', 'summary'],
                ],
            ],
        ];

        try {
            $response = Http::timeout(45)
                ->retry(2, 500, throw: false)
                ->acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post("{$baseUrl}/models/{$model}:generateContent", $payload);

            if ($response->successful()) {
                $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
                
                if (!$text) {
                    return $this->saveOfflineResponse($userId, $request->message, "Maaf, saya sedang mengalami kendala teknis. Silakan coba beberapa saat lagi.");
                }

                $data = json_decode($text, true);

                // JSON Fallback (Anti-Error)
                if (json_last_error() !== JSON_ERROR_NONE || !isset($data['reply'])) {
                    $botReply = $text; // Fallback: ambil semua raw text sebagai reply
                    $emotion = 'Netral';
                    $confidence = 0;
                    $riskLevel = 'LOW';
                    $summary = '';
                } else {
                    $botReply = $data['reply'];
                    $emotion = $data['emotion'] ?? 'Netral';
                    $confidence = isset($data['confidence']) && is_numeric($data['confidence']) && $data['confidence'] >= 0 && $data['confidence'] <= 100 ? (int)$data['confidence'] : 0;
                    $riskLevel = $data['risk_level'] ?? 'LOW';
                    $summary = $data['summary'] ?? '';
                }

            } else {
                $status = $response->status();
                Log::error('Gemini Error', [
                    'status' => $status,
                    'body' => $response->body(),
                    'model' => $model,
                    'user_id' => $userId,
                ]);

                $errorMessage = "Maaf, saya sedang mengalami kendala teknis. Silakan coba beberapa saat lagi.";
                if ($status === 400) $errorMessage = "Permintaan tidak dapat diproses (Koneksi bermasalah).";
                if (in_array($status, [401, 403])) $errorMessage = "Mohon maaf, sistem sedang dalam perbaikan (API Error).";
                if ($status === 404) $errorMessage = "Mohon maaf, sistem sedang dalam perbaikan (Model Error).";
                if ($status === 429) $errorMessage = "Saya sedang berbicara dengan banyak orang, mohon tunggu sebentar ya.";
                
                return $this->saveOfflineResponse($userId, $request->message, $errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage(), [
                'model' => $model,
                'user_id' => $userId,
            ]);
            return $this->saveOfflineResponse($userId, $request->message, "Maaf, saya sedang mengalami kendala teknis. Silakan coba beberapa saat lagi.");
        }

        // DB Transaction untuk menyimpan Pesan, Mood & Chat Session
        DB::transaction(function () use ($userId, $request, $botReply, $emotion, $confidence, $riskLevel, $summary) {
            
            $isCrisis = in_array($riskLevel, ['HIGH', 'CRITICAL']);
            
            $userMessage = Message::create([
                'anonymous_user_id' => $userId,
                'sender' => 'employee',
                'message' => $request->message,
                'is_read' => 0,
                'emotion' => $emotion,
                'confidence' => $confidence,
                'status' => 'baru' 
            ]);

            Message::create([
                'anonymous_user_id' => $userId,
                'sender' => 'bot', 
                'message' => $botReply,
                'is_admin' => 0,
                'is_read' => 0
            ]);

            $dbMood = 'Netral';
            if (in_array($emotion, ['Bahagia', 'Senang'])) $dbMood = 'Senang';
            elseif (in_array($emotion, ['Stress', 'Cemas'])) $dbMood = 'Cemas';
            elseif ($emotion === 'Sedih') $dbMood = 'Sedih';
            elseif ($emotion === 'Marah') $dbMood = 'Marah';

            MoodLog::create([
                'anonymous_user_id' => $userId,
                'message_id' => $userMessage->id,
                'mood' => $dbMood,
                'emotion_label' => strtolower($emotion),
                'confidence_score' => $confidence,
                'notes' => $isCrisis ? 'Krisis Terdeteksi' : 'Terdeteksi otomatis dari chat',
                'source' => 'chat',
                'mood_date' => now()->toDateString()
            ]);
            
            // Update or Create ChatSession for today
            $chatSession = ChatSession::firstOrCreate(
                ['anonymous_user_id' => $userId, 'session_date' => now()->toDateString()],
                ['message_count' => 0, 'risk_level' => 'LOW']
            );
            
            $chatSession->update([
                'summary' => empty($summary) ? $chatSession->summary : $summary,
                'dominant_mood' => $emotion,
                'risk_level' => $riskLevel,
                'message_count' => DB::raw('message_count + 2') // +1 user, +1 bot
            ]);
        });

        return redirect('/chat');
    }

    private function saveOfflineResponse($userId, $userText, $botReply)
    {
        DB::transaction(function () use ($userId, $userText, $botReply) {
            $userMessage = Message::create([
                'anonymous_user_id' => $userId,
                'sender' => 'employee',
                'message' => $userText,
                'is_read' => 0,
                'emotion' => 'Netral',
                'confidence' => 0,
                'status' => 'baru'
            ]);

            Message::create([
                'anonymous_user_id' => $userId,
                'sender' => 'bot', 
                'message' => $botReply,
                'is_admin' => 0,
                'is_read' => 0
            ]);

            MoodLog::create([
                'anonymous_user_id' => $userId,
                'message_id' => $userMessage->id,
                'mood' => 'Netral',
                'emotion_label' => 'netral',
                'confidence_score' => 0,
                'notes' => 'Fallback error',
                'source' => 'chat',
                'mood_date' => now()->toDateString()
            ]);
        });
        
        return redirect('/chat');
    }

    public function messages($id)
    {
        if (session('user_id') != $id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                $msg->message = $this->getReplyText($msg->message);
                return $msg;
            });

        return response()->json($messages);
    }

    private function getReplyText($text)
    {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }
        
        // Cek jika teks berupa JSON atau mengandung field reply
        if ($text[0] === '{' || stripos($text, '"reply"') !== false) {
            $data = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['reply'])) {
                return $data['reply'];
            }
            
            // Regex fallback toleran jika JSON terpotong/error syntax (tidak butuh closing quote wajib)
            if (preg_match('/"reply"\s*:\s*"((?:[^"\\\\]|\\\\.)*)/s', $text, $matches)) {
                $rawString = $matches[1];
                $decoded = json_decode('"' . $rawString . '"');
                return $decoded ?: stripslashes($rawString);
            }
        }
        
        return $text;
    }
}