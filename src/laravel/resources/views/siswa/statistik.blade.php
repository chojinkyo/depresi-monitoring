@extends('layouts.siswa')

@section('title', 'Statistik - Sistem Manajemen Siswa')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/dashboard.css') }}">
@endsection

@php
    $pageTitle = 'Laporan Statistik';
    $pageSubtitle = 'Pantau perkembangan dan riwayat absensi Anda';
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Status Absensi Hari Ini</h5>
                        <h2 class="mb-0 fw-bold">
                            @if($isTodayPresent)
                                <i class="bi bi-check-circle-fill text-success rounded-circle p-1"></i> Sudah Absen
                            @else
                                <i class="bi bi-x-circle-fill text-danger rounded-circle p-1"></i> Belum Absen
                            @endif
                        </h2>
                        <p class="mt-2 mb-0 opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-calendar-check" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Ringkasan Absensi</h5>
                    <div class="row g-3 text-center">
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-success mb-0">{{ $stats['H'] }}</h3>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-info mb-0">{{ $stats['I'] }}</h3>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-warning mb-0">{{ $stats['S'] }}</h3>
                            <small class="text-muted">Sakit</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-danger mb-0">{{ $stats['A'] }}</h3>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mood Chart --}}
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