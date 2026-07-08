@extends('layouts.app')

@section('title','Monitoring AI')
@section('page-title','Monitoring AI')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-3">Monitoring Deteksi Emosi AI</h4>
            <p class="text-muted">Daftar pesan chat dari karyawan beserta hasil deteksi emosi otomatis oleh Artificial Intelligence.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-modern p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Riwayat Deteksi</h5>
                    
                    <!-- Filter -->
                    <form action="/admin/monitoring" method="GET" class="d-flex align-items-center">
                        <label class="me-2 text-muted small">Filter Emosi:</label>
                        <select name="emotion" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="">Semua Emosi</option>
                            <option value="happy" {{ request('emotion') == 'happy' ? 'selected' : '' }}>Senang (Happy)</option>
                            <option value="sad" {{ request('emotion') == 'sad' ? 'selected' : '' }}>Sedih (Sad)</option>
                            <option value="stress" {{ request('emotion') == 'stress' ? 'selected' : '' }}>Stress/Marah/Cemas</option>
                            <option value="neutral" {{ request('emotion') == 'neutral' ? 'selected' : '' }}>Netral</option>
                        </select>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Waktu</th>
                                <th>Isi Pesan</th>
                                <th>Emosi Terdeteksi</th>
                                <th>Confidence</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                            <tr>
                                <td class="fw-semibold text-primary">
                                    {{ $msg->anonymousUser->unique_code ?? 'Unknown' }}
                                </td>
                                <td class="text-muted small">
                                    {{ $msg->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $msg->message }}">
                                        {{ $msg->message }}
                                    </div>
                                </td>
                                <td>
                                    @if(strtolower($msg->emotion) == 'happy' || strtolower($msg->emotion) == 'senang')
                                        <span class="badge bg-success rounded-pill px-3">Happy</span>
                                    @elseif(strtolower($msg->emotion) == 'sad' || strtolower($msg->emotion) == 'sedih')
                                        <span class="badge bg-primary rounded-pill px-3">Sad</span>
                                    @elseif(strtolower($msg->emotion) == 'stress' || strtolower($msg->emotion) == 'marah' || strtolower($msg->emotion) == 'cemas' || strtolower($msg->emotion) == 'takut')
                                        <span class="badge bg-danger rounded-pill px-3">Stress</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">Neutral</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar {{ $msg->confidence >= 80 ? 'bg-success' : ($msg->confidence >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $msg->confidence }}%" 
                                                 aria-valuenow="{{ $msg->confidence }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="fw-bold">{{ $msg->confidence }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="/admin/chat/{{ $msg->anonymous_user_id }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-chat-dots me-1"></i> Balas
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat pesan dari karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $messages->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection
