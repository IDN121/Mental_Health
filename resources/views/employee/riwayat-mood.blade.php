@extends('layouts.app')

@section('title', 'Riwayat Mood')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Riwayat Mood & Emosi</h5>
            <a href="/mood" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Isi Mood
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Emosi</th>
                            <th>Mood/Label</th>
                            <th>Catatan</th>
                            <th>Sumber</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $log)
                            <tr>
                                <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                                <td>
                                    <span class="fs-4">
                                        @php
                                            $moodLower = strtolower($log->mood ?? $log->emotion_label);
                                            if(str_contains($moodLower, 'senang')) echo '😁';
                                            elseif(str_contains($moodLower, 'sedih')) echo '😔';
                                            elseif(str_contains($moodLower, 'marah')) echo '😡';
                                            elseif(str_contains($moodLower, 'cemas') || str_contains($moodLower, 'takut')) echo '😰';
                                            elseif(str_contains($moodLower, 'lelah')) echo '😴';
                                            elseif(str_contains($moodLower, 'kecewa')) echo '😞';
                                            elseif(str_contains($moodLower, 'stres')) echo '🤯';
                                            else echo '😐';
                                        @endphp
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst($log->mood ?? $log->emotion_label) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->notes ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($log->emotion_label)
                                        <span class="badge bg-info text-dark"><i class="bi bi-robot"></i> Analisis Chat AI</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-person"></i> Manual Input</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada riwayat mood.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
