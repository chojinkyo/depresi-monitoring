@extends('layouts.siswa')

@section('title', 'Statistik - Sistem Manajemen Siswa')

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
                                <i class="bi bi-check-circle-fill text-success bg-white rounded-circle p-1"></i> Sudah Absen
                            @else
                                <i class="bi bi-x-circle-fill text-danger bg-white rounded-circle p-1"></i> Belum Absen
                            @endif
                        </h2>
                        <p class="mt-2 mb-0 opacity-75">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-calendar-check" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['H'] }}</h3>
                    <p class="text-muted mb-0">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-info-circle-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['I'] }}</h3>
                    <p class="text-muted mb-0">Izin</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-bandaid-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['S'] }}</h3>
                    <p class="text-muted mb-0">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-x-octagon-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['A'] }}</h3>
                    <p class="text-muted mb-0">Alpha</p>
                </div>
            </div>
        </div>
    </div>
@endsection
