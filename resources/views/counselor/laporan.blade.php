@extends('layouts.app')

@section('title','Laporan Sesi AI')
@section('page-title','Laporan')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-2">Laporan Sesi Konseling (Privacy Safe)</h4>
                <p class="text-muted mb-0">Rekapitulasi rangkuman sesi yang digenerate oleh AI.</p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="/admin/laporan/export-pdf" class="btn btn-danger d-flex align-items-center gap-2 px-3 shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Export ke PDF
                </a>
                <a href="/admin/laporan/export" class="btn btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Export ke CSV
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-modern p-4">
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal Sesi</th>
                                <th>Kode Karyawan</th>
                                <th>Pesan</th>
                                <th>Risk Level</th>
                                <th style="width: 40%">Ringkasan AI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $index => $ses)
                            <tr>
                                <td class="text-muted">{{ $sessions->firstItem() + $index }}</td>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($ses->session_date)->format('d/m/Y') }}</td>
                                <td class="fw-semibold text-primary">{{ $ses->anonymousUser->unique_code ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $ses->message_count }}</span></td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-success';
                                        if($ses->risk_level == 'MEDIUM') $badgeClass = 'bg-warning text-dark';
                                        if($ses->risk_level == 'HIGH') $badgeClass = 'bg-danger';
                                        if($ses->risk_level == 'CRITICAL') $badgeClass = 'bg-dark text-white';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $ses->risk_level }}</span>
                                </td>
                                <td>{{ Str::limit($ses->summary, 80) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat sesi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $sessions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection
