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

        // Default jika AI gagal
        $emotion = "Unknown";
        $confidence = 0;

        // Tembak Flask API
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/predict', [
                'text' => $request->message
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $emotion = $result['emotion'] ?? 'Unknown';
                $confidence = $result['confidence'] ?? 0;
            } else {
                Log::error("Flask API error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Flask API connection failed: " . $e->getMessage());
        }

        // Simpan ke database
        Message::create([
            'anonymous_user_id' => session('user_id'),
            'sender' => 'employee',
            'message' => $request->message,
            'emotion' => $emotion,
            'confidence' => $confidence,
            'is_read' => 0
        ]);

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