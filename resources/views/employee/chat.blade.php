@extends('layouts.app')

@section('title','Chat Konseling')

@section('content')

@include('components.sidebar')

<div class="main-content">

@include('components.navbar')

<div class="card card-modern shadow-sm border-0">

    {{-- HEADER --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">

            <img src="https://ui-avatars.com/api/?name=Counselor&background=2563EB&color=fff"
                class="rounded-circle me-3"
                width="45">

            <div>

                <h5 class="mb-0 fw-bold">
                    Konselor
                </h5>

                <small class="text-success">

                    <i class="bi bi-circle-fill" style="font-size:8px"></i>

                    Online

                </small>

            </div>

        </div>

    </div>

    {{-- CHAT --}}
    <div 
        id="chatBox"
        data-user="{{ session('user_id') }}"
        style="
            height:520px;
            overflow-y:auto;
            padding:25px;
            background:#efeae2;
        ">

        @forelse($messages as $chat)

            @if($chat->sender=='employee')

            <div class="d-flex justify-content-end mb-3">

                <div
                    style="
                    background:#DCF8C6;
                    max-width:70%;
                    padding:12px 16px;
                    border-radius:18px 18px 5px 18px;
                    box-shadow:0 2px 8px rgba(0,0,0,.08);
                    ">

                    <div>

                        {{ $chat->message }}

                    </div>

                    <div class="text-end mt-1">

                        <small class="text-muted">

                            {{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}

                                @if($chat->is_read)
                                    ✔✔
                                @else
                                    ✔
                                @endif
                        </small>

                    </div>

                </div>

            </div>

            @else

            <div class="d-flex justify-content-start mb-3">

                <div
                    style="
                    background:white;
                    max-width:70%;
                    padding:12px 16px;
                    border-radius:18px 18px 18px 5px;
                    box-shadow:0 2px 8px rgba(0,0,0,.08);
                    ">

                    <div>

                        {{ $chat->message }}

                    </div>

                    <div class="text-end mt-1">

                        <small class="text-muted">

                            {{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}

                        </small>

                    </div>

                </div>

            </div>

            @endif

        @empty

        <div class="text-center mt-5 text-muted">

            <i class="bi bi-chat-dots fs-1"></i>

            <br><br>

            Belum ada percakapan.

        </div>

        @endforelse

    </div>

    {{-- INPUT --}}
    <div class="card-footer bg-white">

        <form action="/chat/send" method="POST">

            @csrf

            <div class="d-flex align-items-center">

                {{-- Emoji --}}
                <button
                    type="button"
                    class="btn btn-light rounded-circle me-2">

                    😊

                </button>

                <input
                    type="text"
                    name="message"
                    class="form-control rounded-pill"
                    placeholder="Ketik pesan..."
                    required>

                <button
                    class="btn btn-primary rounded-circle ms-2"
                    style="width:48px;height:48px;">

                    <i class="bi bi-send-fill"></i>

                </button>

            </div>

        </form>

    </div>

</div>

</div>

<script>

<script>

const chatBox=document.getElementById('chatBox');

function scrollBottom(){

    chatBox.scrollTop=chatBox.scrollHeight;

}

scrollBottom();

setInterval(function(){

    fetch('/chat/messages/'+chatBox.dataset.user)

    .then(res=>res.json())

    .then(data=>{

        let html='';

        data.forEach(chat=>{

            if(chat.sender=="employee"){

                html+=`
                <div class="d-flex justify-content-end mb-3">
                    <div style="background:#DCF8C6;max-width:72%;padding:12px 16px;border-radius:18px 18px 5px 18px;">
                        ${chat.message}
                        <div class="text-end">
                            <small>${chat.created_at.substring(11,16)} ${chat.is_read ? '✔✔':'✔'}</small>
                        </div>
                    </div>
                </div>
                `;

            }else{

                html+=`
                <div class="d-flex justify-content-start mb-3">
                    <div style="background:white;max-width:72%;padding:12px 16px;border-radius:18px 18px 18px 5px;">
                        ${chat.message}
                        <div class="text-end">
                            <small>${chat.created_at.substring(11,16)}</small>
                        </div>
                    </div>
                </div>
                `;

            }

        });

        chatBox.innerHTML=html;

        scrollBottom();

    });

},3000);

</script>

</script>

@endsection