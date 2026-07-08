<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Symfony\Component\Process\Process;
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

        // Jalankan AI Python
        $process = new Process([
            'C:\\Users\\Nur alya syahrani\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
            base_path('ai/predict.py'),
            $request->message
        ]);

        $process->run();

        if (!$process->isSuccessful()) {

            // Simpan error ke laravel.log
            Log::error($process->getErrorOutput());
        } 
        else {

            $result = json_decode($process->getOutput(), true);

            if ($result) {
                $emotion = $result['emotion'] ?? 'Unknown';
                $confidence = $result['confidence'] ?? 0;
            }
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