@extends('layouts.app')

@section('title','Detail Chat')

@section('content')

@include('components.sidebar')

<div class="main-content">

    @include('components.navbar')

    <div class="card card-modern p-4">

        {{-- Header Chat --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center">

                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                     style="width:55px;height:55px;font-size:22px;">

                    <i class="bi bi-person"></i>

                </div>

                <div class="ms-3">

                    <h4 class="mb-1">
                        Users Anonim
                    </h4>

                    <small class="text-muted">
                        Kode :
                        EMP-{{ str_pad($user->id,4,'0',STR_PAD_LEFT) }}
                    </small>

                </div>

            </div>

            <button class="btn btn-outline-primary">

                <i class="bi bi-person-lines-fill"></i>

                Detail Karyawan

            </button>

        </div>

        {{-- Isi Chat --}}
        <div 
            id="chatBox"
            data-user="{{ $user->id }}"
                style="
                height:520px;
                overflow-y:auto;
                background:#f8f9fa;
                padding:20px;
                border-radius:15px;
                ">

            @foreach($messages as $chat)

            @if($chat->sender=='employee')

            <div class="d-flex justify-content-start mb-3">

                <div
                    style="
                    background:white;
                    max-width:72%;
                    padding:12px 16px;
                    border-radius:18px 18px 18px 6px;
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

    @else

    <div class="d-flex justify-content-end mb-3">

        <div
            style="
            background:#DCF8C6;
            max-width:72%;
            padding:12px 16px;
            border-radius:18px 18px 6px 18px;
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

@endif

@endforeach

        </div>

        {{-- Footer --}}
        <hr>

            <form action="/admin/chat/{{ $user->id }}" method="POST">

                @csrf

                <div class="d-flex align-items-center">

                <button
                type="button"
                class="btn btn-light rounded-circle me-2">

                😊

                </button>

                    <textarea
                        name="message"
                        class="form-control rounded-pill"
                        rows="1"
                        placeholder="Tulis balasan..."
                        required></textarea>

                    <button

                        class="btn btn-primary rounded-circle ms-2"

                        style="kirim;">

                    <i class="bi bi-send-fill"></i>

                    </button>

                </div>

            </form>

    </div>

</div>

<script>

const chatBox=document.getElementById('chatBox');

function scrollBottom(){

chatBox.scrollTop=chatBox.scrollHeight;

}

scrollBottom();

setInterval(function(){

location.reload();

},3000);

</script>

@endsection