@extends('layouts.app')

@section('title','AI Monitoring & Mood Graph')
@section('page-title','Monitoring AI')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-3">Grafik Pemantauan Mental Health Karyawan</h4>
            <p class="text-muted">Visualisasi berbasis AI untuk memantau tren mood harian dan komposisi emosi keseluruhan.</p>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Tren Mood Harian -->
        <div class="col-lg-8 mb-4">
            <div class="card card-modern p-4 h-100">
                <h5 class="fw-bold mb-4">Tren Mood (7 Hari Terakhir)</h5>
                <canvas id="moodLineChart" height="100"></canvas>
            </div>
        </div>

        <!-- Grafik Distribusi Mood -->
        <div class="col-lg-4 mb-4">
            <div class="card card-modern p-4 h-100">
                <h5 class="fw-bold mb-4">Distribusi Mood Keseluruhan</h5>
                <canvas id="moodDoughnutChart" height="200"></canvas>
            </div>
        </div>
    </div>

    @include('components.footer')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Data for Line Chart
        const dates = {!! json_encode($dates) !!};
        const countsByDate = {!! json_encode($countsByDate) !!};

        const ctxLine = document.getElementById('moodLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Jumlah Entri Mood',
                    data: countsByDate,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Data for Doughnut Chart
        const moodDistribution = {!! json_encode($moodDistribution) !!};
        
        const labels = ['Senang', 'Sedih', 'Marah', 'Cemas', 'Netral'];
        const dataDoughnut = [
            moodDistribution['Senang'] || 0,
            moodDistribution['Sedih'] || 0,
            moodDistribution['Marah'] || 0,
            moodDistribution['Cemas'] || 0,
            moodDistribution['Netral'] || 0
        ];

        const ctxDoughnut = document.getElementById('moodDoughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Distribusi Mood',
                    data: dataDoughnut,
                    backgroundColor: [
                        '#10B981', // Senang (Green)
                        '#3B82F6', // Sedih (Blue)
                        '#EF4444', // Marah (Red)
                        '#F59E0B', // Cemas (Yellow/Orange)
                        '#9CA3AF'  // Netral (Gray)
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

    });
</script>
@endpush
