@extends('layouts.app')

@section('title','Dashboard Admin')

@section('page-title','Dashboard')

@section('content')

@include('components.sidebar')

<div class="main-content">

@include('components.navbar')

@if(isset($highRiskCount) && $highRiskCount > 0)
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
    <div>
        <strong>Peringatan Darurat!</strong> Terdapat <strong>{{ $highRiskCount }} Kasus Risiko Tinggi (HIGH / CRITICAL)</strong> yang memerlukan perhatian segera hari ini.
    </div>
</div>
@endif

<div class="row mb-4">

<div class="col-md-4">
    <div class="card card-modern p-4">
        <div class="d-flex justify-content-between">
            <div>
                <small>Total Karyawan</small>
                <h2>{{ $employeeCount }}</h2>
            </div>
            <div class="stat-icon bg-blue">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card card-modern p-4">
        <div class="d-flex justify-content-between">
            <div>
                <small>Total Chat Tersimpan</small>
                <h2>{{ $chatCount }}</h2>
            </div>
            <div class="stat-icon bg-green">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card card-modern p-4">
        <div class="d-flex justify-content-between">
            <div>
                <small>Laporan Mood Hari Ini</small>
                <h2>{{ $moodCount }}</h2>
            </div>
            <div class="stat-icon bg-orange">
                <i class="bi bi-emoji-smile-fill"></i>
            </div>
        </div>
    </div>
</div>

</div>

<div class="row">

<div class="col-lg-8">
    <div class="card card-modern p-4">
        <h5 class="fw-bold mb-4">
            Sesi Konseling Terbaru
        </h5>
        
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Risk Level</th>
                        <th>Summary AI</th>
                        <th>Waktu Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSessions as $ses)
                    <tr>
                        <td>
                            Karyawan #{{ $ses->anonymousUser->unique_code ?? $ses->anonymous_user_id }}
                        </td>
                        <td>
                            @php
                                $badgeClass = 'bg-success';
                                if($ses->risk_level == 'MEDIUM') $badgeClass = 'bg-warning text-dark';
                                if($ses->risk_level == 'HIGH') $badgeClass = 'bg-danger';
                                if($ses->risk_level == 'CRITICAL') $badgeClass = 'bg-dark text-white';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $ses->risk_level }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($ses->summary, 60) }}</small>
                        </td>
                        <td>
                            <small class="text-muted">{{ $ses->updated_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada sesi tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card card-modern p-4">
        <h5 class="fw-bold mb-3">
            Quick Menu
        </h5>
        <a href="/admin/chat" class="btn btn-primary w-100 mb-3">
            <i class="bi bi-shield-lock-fill me-1"></i> Monitoring Chat (Privacy)
        </a>
        <a href="/admin/statistik" class="btn btn-success w-100 mb-3">
            <i class="bi bi-bar-chart-fill me-1"></i> Statistik Mood
        </a>
        <a href="/admin/laporan" class="btn btn-warning w-100">
            <i class="bi bi-file-earmark-text-fill me-1"></i> Laporan Sesi AI
        </a>
    </div>
</div>

</div>

@include('components.footer')

</div>

@endsection