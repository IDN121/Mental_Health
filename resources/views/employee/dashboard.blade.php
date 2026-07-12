@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
@include('components.sidebar')

<div class="main-content" style="padding: 30px; margin-left: 250px; background-color: #f8f9fa; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Halo, Selamat Datang!</h3>
            <p class="text-muted mb-0">Berikut adalah ringkasan kesehatan mental dan aktivitas Anda.</p>
        </div>
        <div>
            <a href="/chat" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="bi bi-chat-dots-fill me-2"></i> Konseling AI
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Mood Hari Ini -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted fw-bold text-uppercase mb-3">Mood Dominan Hari Ini</h6>
                    <h2 class="fw-bold text-primary mb-0">{{ $moodToday }}</h2>
                </div>
            </div>
        </div>
        <!-- Mood Minggu Ini -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted fw-bold text-uppercase mb-3">Mood Dominan Minggu Ini</h6>
                    <h2 class="fw-bold text-success mb-0">{{ $moodWeek }}</h2>
                </div>
            </div>
        </div>
        <!-- Mood Bulan Ini -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted fw-bold text-uppercase mb-3">Mood Dominan Bulan Ini</h6>
                    <h2 class="fw-bold text-warning mb-0">{{ $moodMonth }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Tren Mood (7 Hari Terakhir)</h5>
                    <canvas id="moodChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('moodChart').getContext('2d');
    const moodChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [{
                label: 'Skor Mood',
                data: {!! json_encode($trendData) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                spanGaps: true,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 5,
                    ticks: {
                        stepSize: 1,
                        font: { size: 13 },
                        callback: function(value) {
                            const labels = ['Stress', 'Sedih', 'Cemas', 'Netral', 'Senang', 'Bahagia'];
                            return labels[value] || '';
                        }
                    },
                    grid: { borderDash: [5, 5] }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 13 } }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const labels = ['Stress', 'Sedih', 'Cemas', 'Netral', 'Senang', 'Bahagia'];
                            return 'Mood: ' + labels[context.raw];
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection