<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;

class AdminChatController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with('anonymousUser')
            ->orderBy('session_date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('counselor.chat.index', compact('sessions'));
    }
}