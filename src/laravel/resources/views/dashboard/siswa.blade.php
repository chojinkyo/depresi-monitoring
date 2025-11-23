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
    <h2 class="section-title">Ringkasan Aktivitas</h2>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Kehadiran Bulan Ini</p>
                    <h3 class="stat-value">92%</h3>
                </div>
                <div class="stat-icon attendance">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Rata-rata Nilai</p>
                    <h3 class="stat-value">87.5</h3>
                </div>
                <div class="stat-icon grades">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Mood Overall</p>
                    <h3 class="stat-value">Sangat Baik 😊</h3>
                </div>
                <div class="stat-icon mood">
                    <i class="bi bi-emoji-smile-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Peringkat</p>
                    <h3 class="stat-value">12 / 30</h3>
                </div>
                <div class="stat-icon rank">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Announcements Row -->
    <div class="row">
        <!-- Mood Chart -->
        <div class="col-lg-7 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Mood Overall 14 Hari</h3>
                    <p class="chart-subtitle">Rata-rata mood 14 hari terakhir: 😊 8.2/10</p>
                </div>
                <div class="chart-content">
                    <div class="mood-chart">
                        <p style="color: #9CA3AF;">Chart akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        <div class="col-lg-5 mb-4">
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
        </div>
    </div>
@endsection
