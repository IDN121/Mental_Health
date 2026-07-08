<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnonymousUser;
use App\Models\Message;

class AdminChatController extends Controller
{
    public function index()
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $users = AnonymousUser::withCount('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('counselor.chat.index', compact('users'));
    }

    public function show($id)
    {
        if (!session()->has('admin_id')) {
            return redirect('/login');
        }

        $user = AnonymousUser::findOrFail($id);

        // Semua pesan dari karyawan dianggap sudah dibaca
        Message::where('anonymous_user_id', $id)
            ->where('sender', 'employee')
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);

        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('counselor.chat.detail', compact(
            'user',
            'messages'
        ));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ]);

        Message::create([
            'anonymous_user_id' => $id,
            'sender' => 'admin',
            'message' => $request->message,
            'emotion' => null,
            'confidence' => null,
            'is_read' => 0
        ]);

        return redirect('/admin/chat/'.$id)
            ->with('success','Balasan berhasil dikirim.');
            
    }

    public function messages($id)
    {
        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}