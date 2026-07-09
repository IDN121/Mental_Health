@extends('layouts.app')

@section('title','Chat Konseling')

@push('styles')
<style>
    /* Adjust main-content specifically for chat to fit screen */
    .chat-container-wrapper {
        height: calc(100vh - 60px); /* 100vh minus some padding from main-content */
        display: flex;
        flex-direction: column;
    }
    
    .chat-card {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        overflow: hidden; /* Keep header and footer in place */
        border-radius: 20px;
    }

    .chat-header {
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 1px solid #e2e8f0;
    }

    .chat-box {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        background-color: #f8f9fa;
        scroll-behavior: smooth;
    }

    .chat-footer {
        border-top: 1px solid #e2e8f0;
        padding: 15px 20px;
        background: #fff;
    }

    .bubble {
        max-width: 65%;
        padding: 12px 18px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        word-break: break-word;
        position: relative;
    }

    .bubble-user {
        background: #ffffff;
        border-radius: 18px 18px 5px 18px;
        border: 1px solid #e2e8f0;
    }

    .bubble-bot {
        background: #EBF5FF;
        border-radius: 18px 18px 18px 5px;
        border: 1px solid #dbeafe;
    }

    .emotion-badge {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 12px;
        margin-bottom: 5px;
        display: inline-block;
        font-weight: 600;
        color: white;
    }

    .emotion-happy { background-color: #10B981; } /* Hijau */
    .emotion-stress { background-color: #F59E0B; color: #fff; } /* Oranye */
    .emotion-sad { background-color: #64748b; } /* Abu-biru */
    .emotion-angry { background-color: #EF4444; } /* Merah */
    .emotion-anxious { background-color: #8B5CF6; } /* Ungu */
    .emotion-default { background-color: #0ea5e9; } /* Biru default */

    /* Scrollbar for chat box */
    .chat-box::-webkit-scrollbar {
        width: 6px;
    }
    .chat-box::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
@endphp
@php
    function getEmotionClass($emotion) {
        $e = strtolower($emotion);
        if (in_array($e, ['happy', 'senang'])) return 'emotion-happy';
        if (in_array($e, ['stress', 'stres'])) return 'emotion-stress';
        if (in_array($e, ['sad', 'sedih'])) return 'emotion-sad';
        if (in_array($e, ['angry', 'marah'])) return 'emotion-angry';
        if (in_array($e, ['anxious', 'cemas', 'takut'])) return 'emotion-anxious';
        return 'emotion-default';
    }
@endphp
@endpush

@section('content')
@include('components.sidebar')

<div class="main-content">
    <!-- Navbar is hidden in chat to save space, or kept minimal -->
    <div class="chat-container-wrapper">
        <div class="card card-modern shadow-sm border-0 chat-card">
            
            {{-- HEADER --}}
            <div class="card-header bg-white d-flex justify-content-between align-items-center chat-header p-3">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=Konselor+AI&background=2563EB&color=fff"
                        class="rounded-circle me-3" width="45">
                    <div>
                        <h5 class="mb-0 fw-bold">Konselor AI</h5>
                        <small class="text-success fw-medium">
                            <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Online
                        </small>
                    </div>
                </div>
                <a href="/employee/dashboard" class="btn btn-light btn-sm text-muted rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            {{-- CHAT BOX --}}
            <div id="chatBox" class="chat-box" data-user="{{ session('user_id') }}">
                @forelse($messages as $chat)
                    @if($chat->sender == 'employee')
                        <!-- USER MESSAGE (RIGHT) -->
                        <div class="d-flex justify-content-end mb-3">
                            <div class="bubble bubble-user">
                                @if($chat->emotion)
                                    <span class="emotion-badge {{ getEmotionClass($chat->emotion) }}">
                                        {{ ucfirst($chat->emotion) }}
                                    </span>
                                @endif
                                <div class="text-dark">{{ $chat->message }}</div>
                                <div class="text-end mt-1">
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                        @if($chat->is_read)
                                            <i class="bi bi-check-all text-primary ms-1"></i>
                                        @else
                                            <i class="bi bi-check ms-1"></i>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- BOT MESSAGE (LEFT) -->
                        <div class="d-flex justify-content-start mb-3">
                            <div class="bubble bubble-bot">
                                <div class="fw-bold text-primary mb-1" style="font-size:12px;">Konselor AI</div>
                                <div class="text-dark">{{ $chat->message }}</div>
                                <div class="text-end mt-1">
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-chat-dots fs-1 text-primary"></i>
                        </div>
                        <h5 class="fw-semibold">Mulai Percakapan</h5>
                        <p class="text-center" style="max-width: 300px;">Ceritakan apa yang kamu rasakan hari ini. Kami siap mendengarkan.</p>
                    </div>
                @endforelse
            </div>

            {{-- INPUT FOOTER --}}
            <div class="chat-footer">
                <form action="/chat/send" method="POST">
                    @csrf
                    <div class="d-flex align-items-center bg-light p-2 rounded-pill border">
                        <input
                            type="text"
                            name="message"
                            class="form-control border-0 bg-transparent shadow-none px-4"
                            placeholder="Tulis balasan..."
                            autocomplete="off"
                            id="chatInput"
                            required>

                        <button
                            type="submit"
                            id="btnSend"
                            class="btn btn-primary rounded-circle ms-2 d-flex justify-content-center align-items-center"
                            style="width:45px;height:45px; flex-shrink: 0;"
                            onclick="showLoading()">
                            
                            <i class="bi bi-send-fill" id="sendIcon"></i>
                            <div class="spinner-border spinner-border-sm d-none" id="sendLoader" role="status"></div>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');

    function showLoading() {
        const input = document.getElementById('chatInput');
        if (input.value.trim() !== '') {
            document.getElementById('sendIcon').classList.add('d-none');
            document.getElementById('sendLoader').classList.remove('d-none');
            document.getElementById('btnSend').disabled = true;
            document.getElementById('btnSend').form.submit();
        }
    }

    function scrollBottom() {
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    function getEmotionClassJs(emotion) {
        if (!emotion) return 'emotion-default';
        const e = emotion.toLowerCase();
        if (['happy', 'senang'].includes(e)) return 'emotion-happy';
        if (['stress', 'stres'].includes(e)) return 'emotion-stress';
        if (['sad', 'sedih'].includes(e)) return 'emotion-sad';
        if (['angry', 'marah'].includes(e)) return 'emotion-angry';
        if (['anxious', 'cemas', 'takut'].includes(e)) return 'emotion-anxious';
        return 'emotion-default';
    }

    scrollBottom();

    // Polling new messages
    setInterval(function(){
        fetch('/chat/messages/' + chatBox.dataset.user)
        .then(res => res.json())
        .then(data => {
            let html = '';
            
            if(data.length === 0) return; // if empty don't override the empty state with nothing
            
            data.forEach(chat => {
                if(chat.sender == "employee"){
                    let emotionBadge = chat.emotion ? `<span class="emotion-badge ${getEmotionClassJs(chat.emotion)}">${chat.emotion.charAt(0).toUpperCase() + chat.emotion.slice(1)}</span>` : '';
                    let checkIcon = chat.is_read ? '<i class="bi bi-check-all text-primary ms-1"></i>' : '<i class="bi bi-check ms-1"></i>';
                    html += `
                    <div class="d-flex justify-content-end mb-3">
                        <div class="bubble bubble-user">
                            ${emotionBadge}
                            <div class="text-dark">${chat.message}</div>
                            <div class="text-end mt-1">
                                <small class="text-muted" style="font-size: 11px;">
                                    ${chat.created_at.substring(11,16)} ${checkIcon}
                                </small>
                            </div>
                        </div>
                    </div>
                    `;
                }else{
                    html += `
                    <div class="d-flex justify-content-start mb-3">
                        <div class="bubble bubble-bot">
                            <div class="fw-bold text-primary mb-1" style="font-size:12px;">Konselor AI</div>
                            <div class="text-dark">${chat.message}</div>
                            <div class="text-end mt-1">
                                <small class="text-muted" style="font-size: 11px;">
                                    ${chat.created_at.substring(11,16)}
                                </small>
                            </div>
                        </div>
                    </div>
                    `;
                }
            });
            chatBox.innerHTML = html;
            // Only scroll if we are near the bottom to avoid annoyance when user is scrolling up?
            // For now just scroll bottom
            scrollBottom();
        });
    }, 3000);
</script>
@endpush

@endsection