@extends('layouts.siswa')

@section('title', 'Diaryku - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Dashboard Diaryku';
    $pageSubtitle = 'Informasi dan tips menjaga kesehatan mental Anda';
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="mb-2 fw-bold">Selamat Datang di Diaryku!</h2>
                        <p class="mb-0 opacity-90" style="font-size: 1.1rem;">Ruang aman untuk memahami dan merawat kesehatan mentalmu.</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Section 1: Apa itu Depresi? --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-question-lg fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Apa itu Depresi?</h4>
                    </div>
                    <p class="text-muted">
                        Depresi bukan sekadar rasa sedih biasa. Ini adalah kondisi kesehatan mental yang serius yang memengaruhi perasaan, cara berpikir, dan tindakan seseorang. Depresi bisa membuat aktivitas sehari-hari terasa berat dan menghilangkan minat pada hal-hal yang biasanya disukai.
                    </p>
                </div>
            </div>
        </div>

        {{-- Section 2: Tanda-tanda Awal --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Tanda-tanda Awal</h4>
                    </div>
                    <ul class="text-muted ps-3 mb-0">
                        <li class="mb-2">Perasaan sedih, cemas, atau "kosong" yang berkepanjangan.</li>
                        <li class="mb-2">Kehilangan minat pada hobi atau aktivitas.</li>
                        <li class="mb-2">Perubahan nafsu makan atau berat badan yang drastis.</li>
                        <li class="mb-2">Sulit tidur (insomnia) atau terlalu banyak tidur.</li>
                        <li class="mb-2">Merasa lelah atau tidak bertenaga hampir setiap hari.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 3: Tips Mengatasi Dini --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-lightbulb-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Langkah Awal Mengatasi Depresi</h4>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">1. Cerita pada Orang Terpercaya</h5>
                                <p class="text-muted small mb-0">Jangan dipendam sendiri. Berbicara dengan teman, keluarga, atau guru BK bisa sangat melegakan bebanmu.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">2. Tetap Aktif Bergerak</h5>
                                <p class="text-muted small mb-0">Olahraga ringan seperti berjalan kaki bisa memicu hormon endorfin yang meningkatkan suasana hati secara alami.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">3. Buat Rutinitas Kecil</h5>
                                <p class="text-muted small mb-0">Mulai dengan target kecil setiap hari, seperti merapikan tempat tidur atau mandi pagi, untuk membangun rasa pencapaian.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">4. Istirahat yang Cukup</h5>
                                <p class="text-muted small mb-0">Kurang tidur bisa memperburuk mood. Usahakan tidur 7-8 jam setiap malam dan kurangi gadget sebelum tidur.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">5. Lakukan Hal yang Disukai</h5>
                                <p class="text-muted small mb-0">Luangkan waktu untuk hobi, mendengarkan musik, atau sekadar duduk santai di taman. Nikmati momen kecil.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light h-100">
                                <h5 class="fw-bold text-dark mb-2">6. Cari Bantuan Profesional</h5>
                                <p class="text-muted small mb-0">Jika perasaan berat tak kunjung hilang, jangan ragu untuk menemui psikolog atau konselor sekolah. Itu tanda kekuatan, bukan kelemahan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Call to Action --}}
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert" style="border-radius: 15px;">
                <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Butuh Teman Cerita?</h5>
                    <p class="mb-0">Jangan ragu untuk menghubungi Guru BK atau layanan konseling sekolah jika kamu merasa butuh bantuan lebih lanjut. Kamu tidak sendirian.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
