<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnonymousUser;
use App\Models\Message;

class KaryawanChatController extends Controller
{
    public function index()
    {
        $users = AnonymousUser::withCount('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('karyawan.chat.index', compact('users'));
    }

    public function show($id)
    {
        $user = AnonymousUser::findOrFail($id);

        // Update status pesan belum terbaca menjadi terbaca
        Message::where('anonymous_user_id', $id)
            ->where('sender', 'employee')
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);

        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('karyawan.chat.detail', compact(
            'user',
            'messages'
        ));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ]);

        // Simpan balasan
        Message::create([
            'anonymous_user_id' => $id,
            'sender' => 'karyawan',
            'message' => $request->message,
            'emotion' => null,
            'confidence' => null,
            'is_read' => 0,
            'status' => 'diproses'
        ]);

        // Update status pesan sebelumnya dari user menjadi 'diproses'
        Message::where('anonymous_user_id', $id)
            ->where('sender', 'employee')
            ->where('status', 'baru')
            ->update([
                'status' => 'diproses'
            ]);

        return redirect('/karyawan/chat/'.$id)
            ->with('success','Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai'
        ]);

        Message::where('anonymous_user_id', $id)
            ->update([
                'status' => $request->status
            ]);

        return redirect('/karyawan/chat/'.$id)
            ->with('success','Status chat berhasil diperbarui.');
    }

    public function messages($id)
    {
        $messages = Message::where('anonymous_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
