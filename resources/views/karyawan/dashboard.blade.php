@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Karyawan</h2>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Pesan Baru</h5>
                <h2 class="mb-0">{{ $chatCount }}</h2>
                <small class="text-white-50">Perlu Dibalas</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0">Chat Aktif & Diproses</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User ID</th>
                        <th>Waktu</th>
                        <th>Pesan Terakhir</th>
                        <th>Emosi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeChats as $chat)
                        <tr>
                            <td>{{ $chat->anonymousUser->unique_code }}</td>
                            <td>{{ $chat->created_at->diffForHumans() }}</td>
                            <td>{{ Str::limit($chat->message, 50) }}</td>
                            <td>
                                @if($chat->emotion)
                                    @php
                                        $badges = [
                                            'senang' => 'success',
                                            'sedih' => 'secondary',
                                            'marah' => 'danger',
                                            'cemas' => 'warning',
                                            'takut' => 'warning',
                                            'kecewa' => 'secondary',
                                            'stres' => 'danger',
                                            'netral' => 'info'
                                        ];
                                        $badge = $badges[strtolower($chat->emotion)] ?? 'primary';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($chat->emotion) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($chat->status == 'baru')
                                    <span class="badge bg-primary">Baru</span>
                                @elseif($chat->status == 'diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @endif
                            </td>
                            <td>
                                <a href="/karyawan/chat/{{ $chat->anonymous_user_id }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-reply-fill"></i> Balas
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada chat aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
