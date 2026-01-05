@extends('layouts.siswa')

@section('title', 'Dashboard - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Dashboard';
    $pageSubtitle = 'Sistem Manajemen Siswa';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/dashboard.css') }}">
@endsection

@section('content')
    <!-- Section Title -->
    <h2 class="section-title h4 mb-4">Ringkasan Aktivitas</h2>

    @php
        
        $attendancePercentage=data_get($attendanceResults, 'persen_hadir', 0);
        $moodLabel=collect($mentalDetails)
        ->groupBy('swafoto_pred')
        ->map(fn ($items) => $items->count())
        ->sortDesc()
        ->keys()
        ->first() ?? '-';

        
    @endphp
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Kehadiran Bulan Ini</p>
                    <h3 class="stat-value">{{ $attendancePercentage }}%</h3>
                </div>
                <div class="stat-icon attendance">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>

        {{-- <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Rata-rata Nilai</p>
                    <h3 class="stat-value">{{ $averageGrade }}</h3>
                </div>
                <div class="stat-icon grades">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div> --}}

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Mood Overall</p>
                    <h3 class="stat-value">{{ $moodLabel }}</h3>
                </div>
                <div class="stat-icon mood">
                    <i class="bi bi-emoji-smile-fill"></i>
                </div>
            </div>
        </div>

        {{-- <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Peringkat</p>
                    <h3 class="stat-value">{{ $rank }}</h3>
                </div>
                <div class="stat-icon rank">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Chart & Announcements Row -->
    <div class="row">
        <!-- Mood Chart -->
        <div class="col-lg-12 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Mood Overall 14 Hari</h3>
                    {{-- <p class="chart-subtitle">Rata-rata mood 14 hari terakhir: {{ $averageMood > 0 ? $averageMood.'/5' : '-' }}</p> --}}
                </div>
                <div class="chart-content">
                    <canvas id="moodChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        {{-- <div class="col-lg-5 mb-4">
            <div class="announcements-card">
                <div class="announcements-header">
                    <h3 class="announcements-title">Pengumuman Terbaru</h3>
                </div>
                <div class="announcements-content">
                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Ujian Matematika</h4>
                            <span class="announcement-badge">2 jam lagi</span>
                        </div>
                        <p class="announcement-description">Persiapkan diri untuk ujian matematika</p>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Libur Nasional</h4>
                            <span class="announcement-badge">3 hari lagi</span>
                        </div>
                        <p class="announcement-description">Sekolah libur pada tanggal 17 Agustus</p>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Ujian Bahasa Indonesia</h4>
                            <span class="announcement-badge besok">Besok</span>
                        </div>
                        <p class="announcement-description">Persiapkan diri untuk ujian bahasa indonesia</p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('moodChart').getContext('2d');
            const labels = [];
            const moodData = @json($mentalDetails);
            const emotionLabels = {
                1: "sadness",
                2: "anger",
                3: "fear",
                4: "disgust",
                5: "happy",
                6: "surprise"
            };
            const emotionLabels2 = Object.fromEntries(Object.entries(emotionLabels).map(([key, value]) => [value, key]))
            function loadPaginatedData(history, page=1)
            {
                // const end=10*page;
                // const start=10*(page-1);
                // const slicedHistory=history.slice(start, end)
                const data=[];
                const xLabels=[];

                history.forEach(e=>{
                    data.push(emotionLabels2[e.swafoto_pred])
                    xLabels.push(" ");
                })

                loadDiagram(data, xLabels)
            }
            
            function loadDiagram(data, xLabels)
            {
                console.log(data);
                let myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: xLabels,
                        datasets: [{
                            
                            data: data,
                            borderWidth: 2,
                            borderColor: "rgba(60,141,188,0.8)",
                            backgroundColor: "rgba(60,141,188,0.2)",
                            fill: false,
                            // garis patah-patah
                            tension: 0 // biar benar-benar patah
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio : false,
                        plugins : {
                            legend : {
                                display : false
                            }
                        },
                        scales: {
                            y  : {
                                display:true,
                                position: 'bottom',
                                min: 0,
                                    max: 7,
                                ticks: {
                                    
                                    step: 1,
                                    callback: function (value) {
                                        return emotionLabels[value];
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    
                                }
                            }
                        }
                    }
                });
            }

            loadPaginatedData(moodData);
        });
    </script>
@endsection
