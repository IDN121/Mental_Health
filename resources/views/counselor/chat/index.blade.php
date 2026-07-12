@extends('layouts.app')

@section('title','Monitoring Chat (Privacy Mode)')

@section('content')

@include('components.sidebar')

<div class="main-content">

@include('components.navbar')

<div class="card card-modern p-4 border-0 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Monitoring Chat (Privacy Mode)</h3>
            <p class="text-muted mb-0">Rangkuman analisis sesi yang dihasilkan otomatis oleh AI.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Karyawan</th>
                    <th>Tanggal Sesi</th>
                    <th>Jumlah Pesan</th>
                    <th>Mood Dominan</th>
                    <th>Risk Level</th>
                    <th style="width: 40%">Ringkasan AI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $ses)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($ses->anonymousUser->unique_code ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold">Karyawan #{{ $ses->anonymousUser->unique_code ?? $ses->anonymous_user_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ses->session_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $ses->message_count }}</span></td>
                        <td>
                            @if($ses->dominant_mood)
                                <span class="badge bg-info text-dark">{{ ucfirst($ses->dominant_mood) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = 'bg-success';
                                if($ses->risk_level == 'MEDIUM') $badgeClass = 'bg-warning text-dark';
                                if($ses->risk_level == 'HIGH') $badgeClass = 'bg-danger';
                                if($ses->risk_level == 'CRITICAL') $badgeClass = 'bg-dark text-white';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $ses->risk_level }}
                            </span>
                        </td>
                        <td>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                {{ $ses->summary ?? 'Sesi baru dimulai, AI belum menghasilkan ringkasan.' }}
                            </p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada sesi chat yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

@endsection