@extends('layouts.app')

@section('title','Chat Konseling')

@push('styles')
<style>
    .main-content {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .chat-container-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
    }
    
    .chat-card {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        overflow: hidden; 
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

    .emotion-bahagia, .emotion-senang { background-color: #10B981; } /* Hijau */
    .emotion-stress, .emotion-cemas { background-color: #F59E0B; color: #fff; } /* Oranye */
    .emotion-sedih { background-color: #64748b; } /* Abu-biru */
    .emotion-marah { background-color: #EF4444; } /* Merah */
    .emotion-netral { background-color: #0ea5e9; } /* Biru default */

    /* Scrollbar for chat box */
    .chat-box::-webkit-scrollbar {
        width: 6px;
    }
    .chat-box::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .typing-indicator {
        font-style: italic;
        color: #64748b;
        font-size: 13px;
        margin-top: 5px;
        display: none;
        padding: 10px 15px;
        background: #f1f5f9;
        border-radius: 15px;
        width: fit-content;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }
</style>
@endphp
@php
    function getEmotionClass($emotion) {
        $e = strtolower($emotion);
        if (in_array($e, ['bahagia', 'senang'])) return 'emotion-bahagia';
        if (in_array($e, ['stress', 'stres', 'cemas', 'takut'])) return 'emotion-cemas';
        if (in_array($e, ['sad', 'sedih'])) return 'emotion-sedih';
        if (in_array($e, ['angry', 'marah'])) return 'emotion-marah';
        return 'emotion-netral';
    }
@endphp
@endpush

@section('content')
@include('components.sidebar')

<div class="main-content">
    <div class="chat-container-wrapper">

        <!-- CHAT INTERFACE -->
        <div class="card card-modern shadow-sm border-0 chat-card">
            
            {{-- HEADER --}}
            <div class="card-header bg-white d-flex justify-content-between align-items-center chat-header p-3">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=Konselor+AI&background=2563EB&color=fff"
                        class="rounded-circle me-3" width="45" alt="AI Avatar">
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
                                <div class="text-dark" style="white-space: pre-wrap;">{{ $chat->message }}</div>
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
                                <div class="text-dark" style="white-space: pre-wrap;">{{ $chat->message }}</div>
                                <div class="text-end mt-1">
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-dark">
                        <div class="card border-0 shadow-sm p-4" style="max-width: 450px; background: #ffffff; border-radius: 15px;">
                            <h4 class="fw-bold text-center text-primary mb-3">👋 Selamat Datang di Ruang Konseling</h4>
                            <p class="text-center text-muted mb-4">Ruang aman untuk berkeluh kesah tanpa menghakimi.</p>
                            
                            <p class="fw-medium mb-2">Kamu dapat bercerita mengenai:</p>
                            <ul class="text-muted mb-4" style="line-height: 1.8;">
                                <li>Pekerjaan & Karir</li>
                                <li>Tekanan Kerja (Burnout)</li>
                                <li>Hubungan dengan rekan kerja / atasan</li>
                                <li>Keluarga & Percintaan</li>
                                <li>Kecemasan (Anxiety)</li>
                                <li>Stres berlebihan</li>
                                <li>Atau hal lain yang sedang mengganggu pikiranmu</li>
                            </ul>
                            
                            <div class="text-center fw-bold text-primary bg-light p-2 rounded">
                                Aku siap mendengarkan.
                            </div>
                        </div>
                    </div>
                @endforelse

                <div id="typingIndicator" class="typing-indicator">
                    Konselor sedang mengetik...
                </div>
            </div>

            {{-- INPUT FOOTER --}}
            <div class="chat-footer">
                <form action="/chat/send" method="POST" id="chatForm">
                    @csrf
                    <div class="d-flex align-items-center bg-light p-2 rounded-pill border">
                        <input
                            type="text"
                            name="message"
                            class="form-control border-0 bg-transparent shadow-none px-4"
                            placeholder="Tulis balasan..."
                            autocomplete="off"
                            id="chatInput"
                            minlength="1"
                            maxlength="3000"
                            required>

                        <button
                            type="submit"
                            id="btnSend"
                            class="btn btn-primary rounded-circle ms-2 d-flex justify-content-center align-items-center"
                            style="width:45px;height:45px; flex-shrink: 0;">
                            
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
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const btnSend = document.getElementById('btnSend');
    const sendIcon = document.getElementById('sendIcon');
    const sendLoader = document.getElementById('sendLoader');
    const typingIndicator = document.getElementById('typingIndicator');

    if(chatForm) {
        chatForm.addEventListener('submit', function(e) {
            if(chatInput.value.trim() === '') {
                e.preventDefault();
                return;
            }

            // Disable button and show loading
            btnSend.disabled = true;
            chatInput.readOnly = true;
            sendIcon.classList.add('d-none');
            sendLoader.classList.remove('d-none');
            
            // Show typing indicator
            if(typingIndicator) {
                typingIndicator.style.display = 'block';
                chatBox.appendChild(typingIndicator); // move to bottom
                scrollBottom();
            }
        });
    }

    function scrollBottom() {
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    function getEmotionClassJs(emotion) {
        if (!emotion) return 'emotion-default';
        const e = emotion.toLowerCase();
        if (['happy', 'senang', 'bahagia'].includes(e)) return 'emotion-bahagia';
        if (['stress', 'stres', 'cemas', 'takut'].includes(e)) return 'emotion-cemas';
        if (['sad', 'sedih'].includes(e)) return 'emotion-sedih';
        if (['angry', 'marah'].includes(e)) return 'emotion-marah';
        return 'emotion-netral';
    }

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    scrollBottom();

    // Polling new messages
    if (chatBox) {
        setInterval(function(){
            // Do not poll if currently sending to avoid UI jumps
            if(btnSend && btnSend.disabled) return;

            fetch('/chat/messages/' + chatBox.dataset.user)
            .then(res => res.json())
            .then(data => {
                if(data.length === 0) return;
                
                let html = '';
                data.forEach(chat => {
                    if(chat.sender == "employee"){
                        let checkIcon = chat.is_read ? '<i class="bi bi-check-all text-primary ms-1"></i>' : '<i class="bi bi-check ms-1"></i>';
                        html += `
                        <div class="d-flex justify-content-end mb-3">
                            <div class="bubble bubble-user">
                                <div class="text-dark" style="white-space: pre-wrap;">${escapeHtml(chat.message)}</div>
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
                                <div class="text-dark" style="white-space: pre-wrap;">${escapeHtml(chat.message)}</div>
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

                // Preserve typing indicator in HTML
                html += `<div id="typingIndicator" class="typing-indicator" style="display: none;">Konselor sedang mengetik...</div>`;
                
                // Only update if content changed (naive check by length to avoid jitter)
                if (chatBox.innerHTML.length !== html.length) {
                    chatBox.innerHTML = html;
                    scrollBottom();
                }
            })
            .catch(err => console.error("Polling error:", err));
        }, 3000);
    }
</script>
@endpush

@endsection