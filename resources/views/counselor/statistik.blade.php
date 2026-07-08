@extends('layouts.app')

@section('title','Statistik & Grafik Mood')
@section('page-title','Statistik')

@section('content')

@include('components.sidebar')

<div class="main-content">
    @include('components.navbar')

    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-3">Statistik Kesehatan Mental Karyawan</h4>
            <p class="text-muted">Visualisasi berbasis AI untuk memantau tren mood harian dan komposisi emosi keseluruhan.</p>
        </div>
    </div>

    <!-- Emotion Stat Cards -->
    <div class="row mb-4">
        @php
            $emotions = [
                ['name' => 'Senang', 'color' => 'success', 'icon' => 'bi-emoji-smile'],
                ['name' => 'Sedih', 'color' => 'primary', 'icon' => 'bi-emoji-frown'],
                ['name' => 'Marah', 'color' => 'danger', 'icon' => 'bi-emoji-angry'],
                ['name' => 'Cemas', 'color' => 'warning', 'icon' => 'bi-emoji-dizzy'],
                ['name' => 'Netral', 'color' => 'secondary', 'icon' => 'bi-emoji-neutral']
            ];
        @endphp

        @foreach($emotions as $emo)
        <div class="col-lg mb-3">
            <div class="card card-modern h-100 p-3 text-center d-flex flex-column justify-content-center">
                <div class="text-{{ $emo['color'] }} mb-2">
                    <i class="bi {{ $emo['icon'] }} fs-1"></i>
                </div>
                <h6 class="text-muted mb-1">{{ $emo['name'] }}</h6>
                <h3 class="fw-bold text-dark mb-0">{{ $moodDistribution[$emo['name']] ?? 0 }}</h3>
                <small class="text-muted">{{ $moodPercentages[$emo['name']] ?? 0 }}%</small>
            </div>
        </div>
        @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        Chart.register(ChartDataLabels);

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
                    },
                    datalabels: {
                        display: false
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
        const totalMoods = {!! json_encode($totalMoods) !!};
        
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
                        '#6C757D'  // Netral (Gray)
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value, ctx) => {
                            if (value === 0) return '';
                            let percentage = (value * 100 / totalMoods).toFixed(1) + "%";
                            return percentage;
                        }
                    }
                }
            }
        });

    });
</script>
@endpush
