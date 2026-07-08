@extends('layouts.app')

@section('title','Laporan Riwayat Chat')
@section('page-title','Laporan')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-2">Laporan Riwayat Chat Karyawan</h4>
                <p class="text-muted mb-0">Rekapitulasi seluruh pesan masuk beserta hasil deteksi emosi.</p>
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
                                <th>Tanggal & Waktu</th>
                                <th>Kode Karyawan</th>
                                <th style="width: 40%">Pesan</th>
                                <th>Emosi (AI)</th>
                                <th>Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $index => $msg)
                            <tr>
                                <td class="text-muted">{{ $messages->firstItem() + $index }}</td>
                                <td class="text-nowrap">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-semibold text-primary">{{ $msg->anonymousUser->unique_code ?? '-' }}</td>
                                <td>{{ $msg->message }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ strtoupper($msg->emotion) }}</span>
                                </td>
                                <td>{{ $msg->confidence }}%</td>
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
                    {{ $messages->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection
